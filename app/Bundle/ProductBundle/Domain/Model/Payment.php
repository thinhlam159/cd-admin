<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class Payment
{
    /**
     * @var PaymentId
     */
    private PaymentId $paymentId;

    /**
     * @var int
     */
    private int $cost;

    /**
     * @var MonetaryUnitType
     */
    private MonetaryUnitType $monetaryUnitType;

    /**
     * @var string|null
     */
    private ?string $comment;

    /**
     * @var string
     */
    private string $customerId;

    /**
     * @var string
     */
    private string $userId;

    /**
     * @var int
     */
    private int $date;

    /**
     * @param PaymentId $paymentId
     * @param int $cost
     * @param MonetaryUnitType $monetaryUnitType
     * @param string|null $comment
     * @param string $customerId
     * @param string $userId
     * @param int $date
     */
    public function __construct(PaymentId $paymentId, int $cost, MonetaryUnitType $monetaryUnitType, ?string $comment, string $customerId, string $userId, int $date)
    {
        $this->paymentId = $paymentId;
        $this->cost = $cost;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->comment = $comment;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->date = $date;
    }

    /**
     * @return PaymentId
     */
    public function getPaymentId(): PaymentId
    {
        return $this->paymentId;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @return MonetaryUnitType
     */
    public function getMonetaryUnitType(): MonetaryUnitType
    {
        return $this->monetaryUnitType;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }
}
