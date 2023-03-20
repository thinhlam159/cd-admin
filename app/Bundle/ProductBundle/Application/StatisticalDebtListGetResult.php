<?php

namespace App\Bundle\ProductBundle\Application;

final class StatisticalDebtListGetResult
{
    /**
     * @var StatisticalRevenuesDayResult[]
     */
    public array $revenuesDayResults;

    /**
     * @param StatisticalRevenuesDayResult[] $revenuesDayResults
     */
    public function __construct(array $revenuesDayResults)
    {
        $this->revenuesDayResults = $revenuesDayResults;
    }
}
