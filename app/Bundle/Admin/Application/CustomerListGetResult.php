<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Common\Application\PaginationResult;

class CustomerListGetResult
{
    public array $customerResults;
    public PaginationResult $paginationResult;

    /**
     * @param CustomerResult[] $customerResults customerResults
     * @param PaginationResult $paginationResult paginationResult
     */
    public function __construct(array $customerResults, PaginationResult $paginationResult)
    {
        $this->customerResults = $customerResults;
        $this->paginationResult = $paginationResult;
    }
}
