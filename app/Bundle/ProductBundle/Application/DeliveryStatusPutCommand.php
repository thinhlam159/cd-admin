<?php

namespace App\Bundle\ProductBundle\Application;

final class DeliveryStatusPutCommand
{
    /**
     * @var string
     */
    public string $orderId;

    /**
     * @var string
     */
    public string $deliveryStatus;

    /**
     * @param string $orderId
     * @param string $deliveryStatus
     */
    public function __construct(string $orderId, string $deliveryStatus)
    {
        $this->orderId = $orderId;
        $this->deliveryStatus = $deliveryStatus;
    }
}
