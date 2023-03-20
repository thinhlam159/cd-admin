<?php

namespace App\Bundle\ProductBundle\Application;

final class StatisticalRevenuesDayResult
{
    /**
     * @var string
     */
    public string $date;

    /**
     * @var int
     */
    public int $total;

    /**
     * @param string $date
     * @param int $total
     */
    public function __construct(string $date, int $total)
    {
        $this->date = $date;
        $this->total = $total;
    }
}
