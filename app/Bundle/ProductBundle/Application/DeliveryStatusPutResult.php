<?php

namespace App\Bundle\ProductBundle\Application;

class DeliveryStatusPutResult
{
    /**
     * @var string
     */
    public string $orderId;

    /**
     * @param string $orderId
     */
    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
