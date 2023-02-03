<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

final class StatisticalPeriodRevenueGetResult
{
    /**
     * @var int
     */
    public int $total;

    /**
     * @param int $total
     */
    public function __construct(int $total)
    {
        $this->total = $total;
    }
}
