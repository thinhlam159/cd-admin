<?php

namespace App\Bundle\ProductBundle\Application;

class ContainerOrderCustomerListGetCommand
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @param string $customerId
     */
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }
}
