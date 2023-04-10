<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalDebtListGetCommand
{
    /**
     * @var array
     */
    public array $categoryIds;

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
     * @param array $categoryIds
     * @param string|null $date
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(array $categoryIds, ?string $date, ?string $startDate, ?string $endDate)
    {
        $this->categoryIds = $categoryIds;
        $this->date = $date;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
