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
     * @var int
     */
    public int $date;

    /**
     * @var OrderProductCommand[]
     */
    public array $orderProductCommands;

    /**
     * @param string $customerId
     * @param string $userId
     * @param int $date
     * @param OrderProductCommand[] $orderProductCommands
     */
    public function __construct(string $customerId, string $userId, int $date, array $orderProductCommands)
    {
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->date = $date;
        $this->orderProductCommands = $orderProductCommands;
    }
}
