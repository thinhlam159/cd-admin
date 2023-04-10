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
     * @var string
     */
    public string $date;

    /**
     * @var OrderProductCommand[]
     */
    public array $orderProductCommands;

    /**
     * @param string $customerId
     * @param string $userId
     * @param string $date
     * @param OrderProductCommand[] $orderProductCommands
     */
    public function __construct(string $customerId, string $userId, string $date, array $orderProductCommands)
    {
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->date = $date;
        $this->orderProductCommands = $orderProductCommands;
    }
}
