<?php

namespace App\Bundle\ProductBundle\Application;

final class OrderCancelPostCommand
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
