<?php

namespace App\Bundle\ProductBundle\Application;

class PaymentPostCommand
{
    /**
     * @var int
     */
    public int $cost;

    /**
     * @var string
     */
    public string $monetaryUnitType;

    /**
     * @var string|null
     */
    public ?string $comment;

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
     * @param int $cost
     * @param string $monetaryUnitType
     * @param string|null $comment
     * @param string $customerId
     * @param string $userId
     * @param string $date
     */
    public function __construct(int $cost, string $monetaryUnitType, ?string $comment, string $customerId, string $userId, string $date)
    {
        $this->cost = $cost;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->comment = $comment;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->date = $date;
    }
}
