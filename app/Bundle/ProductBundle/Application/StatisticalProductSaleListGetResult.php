<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

final class StatisticalProductSaleListGetResult
{
    /**
     * @var StatisticalProductSaleResult[]
     */
    public array $statisticalProductSaleResults;

    /**
     * @param StatisticalProductSaleResult[] $statisticalProductSaleResults
     */
    public function __construct(array $statisticalProductSaleResults)
    {
        $this->statisticalProductSaleResults = $statisticalProductSaleResults;
    }
}
