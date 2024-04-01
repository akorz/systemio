<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\CouponFixtures;
use App\DataFixtures\ProductFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class OrderTest extends ApiTestCase
{
    protected EntityManagerInterface $entityManager;
    protected function setUp(): void
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $this->entityManager = $doctrine->getManager();

        $databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([
            ProductFixtures::class,
            CouponFixtures::class
        ]);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $ormPurger = new ORMPurger($this->entityManager);
        $ormPurger->purge();

        $this->entityManager->close();

        parent::tearDown();
    }

    public function testCalculator(): void
    {
        $response = $this->createClient()->request('POST', '/calculate-price', ['json' => [
            'product' => 4,
            'taxNumber' => 'XXXX',
            'couponCode' => 'YYYY',
        ]]);

        $this->assertResponseStatusCodeSame(400);

        $response = $this->createClient()->request('POST', '/calculate-price', ['json' => [
            'product' => 1,
            'taxNumber' => 'DE123567891',
            'couponCode' => 'P10',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['price' => 107.1]);

        // you can add any tests....
    }

    public function testPurchase(): void
    {
        $response = $this->createClient()->request('POST', '/purchase', ['json' => [
            'product' => 4,
            'taxNumber' => 'XXXX',
            'couponCode' => 'YYYY',
            'paymentProcessor' => 'ZZZZ',
        ]]);

        $this->assertResponseStatusCodeSame(400);

        $response = $this->createClient()->request('POST', '/purchase', ['json' => [
            'product' => 3,
            'taxNumber' => 'DE123567891',
            'couponCode' => 'A50',
            'paymentProcessor' => 'stripe'
        ]]);

        $this->assertResponseStatusCodeSame(400);

        $response = $this->createClient()->request('POST', '/purchase', ['json' => [
            'product' => 1,
            'taxNumber' => 'DE123567891',
            'couponCode' => 'P10',
            'paymentProcessor' => 'paypal'
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['success' => 'Payment complete']);

        // you can add any tests....
    }
}
