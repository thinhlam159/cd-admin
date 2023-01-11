<?php

namespace App\Bundle\ProductBundle\Application;

class ContainerOrderPostCommand
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
     * @var int
     */
    public int $date;

    /**
     * @param int $cost
     * @param string $monetaryUnitType
     * @param string|null $comment
     * @param string $customerId
     * @param string $userId
     * @param int $date
     */
    public function __construct(int $cost, string $monetaryUnitType, ?string $comment, string $customerId, string $userId, int $date)
    {
        $this->cost = $cost;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->comment = $comment;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->date = $date;
    }
}
