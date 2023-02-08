<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class ContainerOrder
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ContainerOrderId
     */
    private ContainerOrderId $containerOrderId;

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
     * @var OrderPaymentStatus
     */
    private OrderPaymentStatus $paymentStatus;

    /**
     * @var SettingDate
     */
    private SettingDate $arisingDate;

    /**
     * @param ContainerOrderId $containerOrderId
     * @param int $cost
     * @param MonetaryUnitType $monetaryUnitType
     * @param string|null $comment
     * @param CustomerId $customerId
     * @param UserId $userId
     * @param OrderPaymentStatus $paymentStatus
     * @param SettingDate $arisingDate
     */
    public function __construct(ContainerOrderId $containerOrderId, int $cost, MonetaryUnitType $monetaryUnitType, ?string $comment, CustomerId $customerId, UserId $userId, OrderPaymentStatus $paymentStatus, SettingDate $arisingDate)
    {
        $this->containerOrderId = $containerOrderId;
        $this->cost = $cost;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->comment = $comment;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->paymentStatus = $paymentStatus;
        $this->arisingDate = $arisingDate;
    }

    /**
     * @return ContainerOrderId
     */
    public function getContainerOrderId(): ContainerOrderId
    {
        return $this->containerOrderId;
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
     * @return OrderPaymentStatus
     */
    public function getPaymentStatus(): OrderPaymentStatus
    {
        return $this->paymentStatus;
    }

    /**
     * @return SettingDate
     */
    public function getArisingDate(): SettingDate
    {
        return $this->arisingDate;
    }
}
