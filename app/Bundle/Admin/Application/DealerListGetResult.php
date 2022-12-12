<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Common\Application\PaginationResult;

class DealerListGetResult
{
    /**
     * @var DealerResult[]
     */
    public array $dealerResults;
    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param DealerResult[] $dealerResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(array $dealerResults, PaginationResult $paginationResult)
    {
        $this->dealerResults = $dealerResults;
        $this->paginationResult = $paginationResult;
    }
}
