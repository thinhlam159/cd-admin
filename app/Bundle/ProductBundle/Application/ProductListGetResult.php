<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class ProductListGetResult
{
    /**
     * @var ProductResult[]
     */
    public array $productResults;

    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param ProductResult[] $productResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(
        array $productResults,
        PaginationResult $paginationResult
    )
    {
        $this->productResults = $productResults;
        $this->paginationResult = $paginationResult;
    }
}
