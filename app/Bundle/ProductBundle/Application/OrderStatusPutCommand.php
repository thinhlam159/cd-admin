<?php

namespace App\Bundle\ProductBundle\Application;

class OrderStatusPutCommand
{
    /**
     * @var string
     */
    public string $orderId;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @param string $orderId
     * @param string $userId
     */
    public function __construct(string $orderId, string $userId)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
    }
}
