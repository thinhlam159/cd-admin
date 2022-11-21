<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class CategoryListGetResult
{
    /**
     * @var CategoryResult[]
     */
    public array $categoryResults;

    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param CategoryResult[] $categoryResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(
        array $categoryResults,
        PaginationResult $paginationResult
    )
    {
        $this->categoryResults = $categoryResults;
        $this->paginationResult = $paginationResult;
    }
}
