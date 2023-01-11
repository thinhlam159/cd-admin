<?php

namespace App\Bundle\ProductBundle\Application;

final class ProductAttributePriceListGetResult
{
    /**
     * @var ProductAttributePriceResult[]
     */
    public array $productAttributePriceResults;

    /**
     * @param ProductAttributePriceResult[] $productAttributePriceResults
     */
    public function __construct(array $productAttributePriceResults)
    {
        $this->productAttributePriceResults = $productAttributePriceResults;
    }
}
