<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

final class StatisticalDebtListGetResult
{
    /**
     * @var DebtResult[]
     */
    public array $debtResults;

    /**
     * @param DebtResult[] $debtResults
     */
    public function __construct(array $debtResults)
    {
        $this->debtResults = $debtResults;
    }
}
