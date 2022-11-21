<?php

namespace App\Bundle\ProductBundle\Application;

class OrderPostCommand
{
    /**
     * @var string
     */
    public string $customerId;
    /**
     * @var string
     */
    public string $userId;
    /**
     * @var OrderProductCommand[]
     */
    public array $orderProductCommands;

    /**
     * @param string $customerId
     * @param string $userId
     * @param OrderProductCommand[] $orderProductCommands
     */
    public function __construct(string $customerId, string $userId, array $orderProductCommands)
    {
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->orderProductCommands = $orderProductCommands;
    }
}
