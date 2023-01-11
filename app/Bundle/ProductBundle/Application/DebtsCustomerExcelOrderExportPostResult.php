<?php

namespace App\Bundle\ProductBundle\Application;

final class DebtsCustomerExcelOrderExportPostResult
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
