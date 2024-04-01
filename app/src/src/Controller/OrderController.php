<?php

namespace App\Controller;

use App\Calculator\PriceCalculator;
use App\DTO\CalculateDTO;
use App\DTO\PurchaseDTO;
use App\Factory\PaymentFactory;
use App\Service\CalculatePrice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(protected CalculatePrice $calculatePrice) {

    }

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(#[MapRequestPayload] CalculateDTO $calculateDTO): JsonResponse
    {
        return $this->json(['price' => $this->calculatePrice->calculate($calculateDTO)]);
    }

    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(#[MapRequestPayload] PurchaseDTO $purchaseDTO): JsonResponse
    {
        $paymentMethod = PaymentFactory::getPaymentMethod($purchaseDTO->paymentProcessor);
        $price = $this->calculatePrice->calculate($purchaseDTO);

        try {
            $paymentMethod->process($price);
        }
        catch (\Exception $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['success' => 'Payment complete']);
    }
}
