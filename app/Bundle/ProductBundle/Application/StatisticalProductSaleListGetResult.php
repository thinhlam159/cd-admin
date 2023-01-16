<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

final class StatisticalProductSaleListGetResult
{
    /**
     * @var OrderResult[]
     */
    public array $orderResults;

    /**
     * @param OrderResult[] $orderResults
     */
    public function __construct(array $orderResults)
    {
        $this->orderResults = $orderResults;
    }
}
