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
     * @var CustomerId
     */
    private CustomerId $customerId;

    /**
     * @var UserId
     */
    private UserId $userId;

    /**
     * @var SettingDate
     */
    private SettingDate $date;

    /**
     * @var PaymentStatus
     */
    private PaymentStatus $paymentStatus;

    /**
     * @param PaymentId $paymentId
     * @param int $cost
     * @param MonetaryUnitType $monetaryUnitType
     * @param string|null $comment
     * @param CustomerId $customerId
     * @param UserId $userId
     * @param SettingDate $date
     * @param PaymentStatus $paymentStatus
     */
    public function __construct(PaymentId $paymentId, int $cost, MonetaryUnitType $monetaryUnitType, ?string $comment, CustomerId $customerId, UserId $userId, SettingDate $date, PaymentStatus $paymentStatus)
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
     * @return CustomerId
     */
    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return SettingDate
     */
    public function getDate(): SettingDate
    {
        return $this->date;
    }

    /**
     * @return PaymentStatus
     */
    public function getPaymentStatus(): PaymentStatus
    {
        return $this->paymentStatus;
    }


    /**
     * @return void
     * @throws \App\Bundle\Common\Domain\Model\InvalidArgumentException
     */
    public function updateResolvedStatus(): void
    {
        $this->paymentStatus = PaymentStatus::fromStatus(PaymentStatus::RESOLVED);
    }

    /**
     * @return void
     * @throws \App\Bundle\Common\Domain\Model\InvalidArgumentException
     */
    public function updateCancelStatus(): void
    {
        $this->paymentStatus = PaymentStatus::fromStatus(PaymentStatus::CANCEL);
    }
}
