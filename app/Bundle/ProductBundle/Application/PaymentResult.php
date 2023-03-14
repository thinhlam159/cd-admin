<?php

namespace App\Bundle\ProductBundle\Application;

class PaymentResult
{
    /**
     * @var string
     */
    public string $paymentId;

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
     * @var string
     */
    public string $paymentStatus;

    /**
     * @param string $paymentId
     * @param int $cost
     * @param string $monetaryUnitType
     * @param string|null $comment
     * @param string $customerId
     * @param string $userId
     * @param string $date
     * @param string $paymentStatus
     */
    public function __construct(string $paymentId, int $cost, string $monetaryUnitType, ?string $comment, string $customerId, string $userId, string $date, string $paymentStatus)
    {
        $this->paymentId = $paymentId;
        $this->cost = $cost;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->comment = $comment;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->date = $date;
        $this->paymentStatus = $paymentStatus;
    }
}
