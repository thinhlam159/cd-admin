<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

final class CustomerDebtHistoryListGetResult
{
    /**
     * @var DebtResult[]
     */
    public array $debtResults;

    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param DebtResult[] $debtResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(array $debtResults, PaginationResult $paginationResult)
    {
        $this->debtResults = $debtResults;
        $this->paginationResult = $paginationResult;
    }
}
