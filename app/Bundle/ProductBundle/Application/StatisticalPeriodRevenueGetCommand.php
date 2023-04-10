<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalPeriodRevenueGetCommand
{
    /**
     * @var string|null
     */
    public ?string $startDate;

    /**
     * @var string|null
     */
    public ?string $endDate;

    /**
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(?string $startDate, ?string $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
