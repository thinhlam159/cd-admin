<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class ImportGoodListGetResult
{
    /**
     * @var ImportGoodResult[]
     */
    public array $importGoodResults;

    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param ImportGoodResult[] $importGoodResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(array $importGoodResults, PaginationResult $paginationResult)
    {
        $this->importGoodResults = $importGoodResults;
        $this->paginationResult = $paginationResult;
    }
}
