<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class ProductAttributeValueListGetResult
{
    /**
     * @var ProductAttributeValueResult[]
     */
    public array $productAttributeValueResults;

    /**
     * @param ProductAttributeValueResult[] $productAttributeValueResults
     */
    public function __construct(array $productAttributeValueResults)
    {
        $this->productAttributeValueResults = $productAttributeValueResults;
    }
}
