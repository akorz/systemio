<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['iPhone', 100],
            ['Headphones', 20],
            ['Case', 10],
        ];

        for ($i = 0; $i < count($products); $i++) {
            $product = new Product();
            $product->setName($products[$i][0]);
            $product->setPrice($products[$i][1]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
