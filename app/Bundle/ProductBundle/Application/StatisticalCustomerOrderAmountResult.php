<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalCustomerOrderAmountResult
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string
     */
    public string $customerName;

    /**
     * @var int
     */
    public int $count;

    /**
     * @param string $customerId
     * @param string $customerName
     * @param int $count
     */
    public function __construct(
        string $customerId,
        string $customerName,
        int $count
    )
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->count = $count;
    }
}
