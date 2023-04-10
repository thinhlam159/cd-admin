<?php

namespace App\Bundle\ProductBundle\Application;

final class DebtsCustomerExcelOrderExportPostResult
{
    /**
     * @var DebtCustomerExcelExportResult
     */
    public DebtCustomerExcelExportResult $customerExcelExportResult;

    /**
     * @var DebtResult[]
     */
    public array $debtResults;

    /**
     * @param DebtCustomerExcelExportResult $customerExcelExportResult
     * @param DebtResult[] $debtResults
     */
    public function __construct(DebtCustomerExcelExportResult $customerExcelExportResult, array $debtResults)
    {
        $this->customerExcelExportResult = $customerExcelExportResult;
        $this->debtResults = $debtResults;
    }
}
