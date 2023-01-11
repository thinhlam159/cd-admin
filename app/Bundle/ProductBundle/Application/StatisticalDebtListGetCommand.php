<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalDebtListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $date;

    /**
     * @var string|null
     */
    public ?string $startDate;

    /**
     * @var string|null
     */
    public ?string $endDate;

    /**
     * @param string|null $date
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(?string $date, ?string $startDate, ?string $endDate)
    {
        $this->date = $date;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
