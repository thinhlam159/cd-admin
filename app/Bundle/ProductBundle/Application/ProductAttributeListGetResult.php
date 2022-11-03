<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;

class ProductAttributeListGetResult
{
    /**
     * @var ProductAttributeResult[]
     */
    public array $productAttributeResults;

    /**
     * @param ProductAttributeResult[] $productAttributeResults
     */
    public function __construct(array $productAttributeResults)
    {
        $this->productAttributeResults = $productAttributeResults;
    }
}
