<?php

namespace App\Bundle\ProductBundle\Application;

final class ProductPriceAllListGetResult
{
    /**
     * @var ProductPriceResult[]
     */
    public array $productPriceResults;

    /**
     * @param ProductPriceResult[] $productPriceResults
     */
    public function __construct(array $productPriceResults)
    {
        $this->productPriceResults = $productPriceResults;
    }
}
