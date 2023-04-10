<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class ExportGoodListGetResult
{
    /**
     * @var ExportGoodResult[]
     */
    public array $exportGoodResults;

    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param ExportGoodResult[] $exportGoodResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(array $exportGoodResults, PaginationResult $paginationResult)
    {
        $this->exportGoodResults = $exportGoodResults;
        $this->paginationResult = $paginationResult;
    }
}
