<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalCountCustomerOrderGetCommand
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string|null
     */
    public ?string $startDate;

    /**
     * @var string|null
     */
    public ?string $endDate;

    /**
     * @param string $customerId
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(string $customerId, ?string $startDate, ?string $endDate)
    {
        $this->customerId = $customerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
