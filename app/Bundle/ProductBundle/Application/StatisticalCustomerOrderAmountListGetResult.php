<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

final class StatisticalCustomerOrderAmountListGetResult
{
    /**
     * @var StatisticalCustomerOrderAmountResult[]
     */
    public array $statisticalCustomerOrderAmountResults;

    /**
     * @param StatisticalCustomerOrderAmountResult[] $statisticalCustomerOrderAmountResults
     */
    public function __construct(array $statisticalCustomerOrderAmountResults)
    {
        $this->statisticalCustomerOrderAmountResults = $statisticalCustomerOrderAmountResults;
    }
}
