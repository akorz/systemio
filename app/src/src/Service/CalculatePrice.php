<?php

namespace App\Service;

use App\Calculator\PriceCalculator;
use App\Repository\ProductRepository;

class CalculatePrice
{
    public function __construct(protected ProductRepository $productRepository, protected PriceCalculator $priceCalculator) {

    }

    public function calculate(mixed $context): float {
        $product = $this->productRepository->find($context->product);

        if (!$product)
            throw new \Exception('Product not found');

        return $this->priceCalculator->calculate($product->getPrice(), $context);
    }
}