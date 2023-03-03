<?php

namespace App\Bundle\ProductBundle\Application;

class DebtCustomerExcelExportResult
{
    /**
     * @var string
     */
    public string $customerName;

    /**
     * @var int
     */
    public int $totalDebt;

    /**
     * @var int
     */
    public int $totalPayment;

    /**
     * @var int
     */
    public int $restDebt;

    /**
     * @param string $customerName
     * @param int $totalDebt
     * @param int $totalPayment
     * @param int $restDebt
     */
    public function __construct(string $customerName, int $totalDebt, int $totalPayment, int $restDebt)
    {
        $this->customerName = $customerName;
        $this->totalDebt = $totalDebt;
        $this->totalPayment = $totalPayment;
        $this->restDebt = $restDebt;
    }
}
