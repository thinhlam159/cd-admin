<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class DebtHistory
{
    /**
     * @var DebtHistoryId
     */
    private DebtHistoryId $debtHistoryId;

    /**
     * @var CustomerId
     */
    private CustomerId $customerId;

    /**
     * @var UserId
     */
    private UserId $userId;

    /**
     * @var int
     */
    private int $totalDebt;

    /**
     * @var int
     */
    private int $totalPayment;

    /**
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @var DebtHistoryUpdateType
     */
    private DebtHistoryUpdateType $debtHistoryUpdateType;

    /**
     * @var OrderId|null
     */
    private ?OrderId $orderId;

    /**
     * @var ContainerOrderId|null
     */
    private ?ContainerOrderId $containerOrderId;

    /**
     * @var VatId|null
     */
    private ?VatId $vatId;

    /**
     * @var PaymentId|null
     */
    private ?PaymentId $paymentId;

    /**
     * @var OtherDebtId|null
     */
    private ?OtherDebtId $otherDebtId;

    /**
     * @var int
     */
    private int $numberOfMoney;

    /**
     * @var int
     */
    private int $updateDate;

    /**
     * @var MonetaryUnitType
     */
    private MonetaryUnitType $monetaryUnitType;

    /**
     * @var int
     */
    private int $version;

    /**
     * @param DebtHistoryId $debtHistoryId
     * @param CustomerId $customerId
     * @param UserId $userId
     * @param int $totalDebt
     * @param int $totalPayment
     * @param bool $isCurrent
     * @param DebtHistoryUpdateType $debtHistoryUpdateType
     * @param OrderId|null $orderId
     * @param ContainerOrderId|null $containerOrderId
     * @param VatId|null $vatId
     * @param PaymentId|null $paymentId
     * @param OtherDebtId|null $otherDebtId
     * @param int $numberOfMoney
     * @param int $updateDate
     * @param MonetaryUnitType $monetaryUnitType
     * @param int $version
     */
    public function __construct(DebtHistoryId $debtHistoryId, CustomerId $customerId, UserId $userId, int $totalDebt, int $totalPayment, bool $isCurrent, DebtHistoryUpdateType $debtHistoryUpdateType, ?OrderId $orderId, ?ContainerOrderId $containerOrderId, ?VatId $vatId, ?PaymentId $paymentId, ?OtherDebtId $otherDebtId, int $numberOfMoney, int $updateDate, MonetaryUnitType $monetaryUnitType, int $version)
    {
        $this->debtHistoryId = $debtHistoryId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->totalDebt = $totalDebt;
        $this->totalPayment = $totalPayment;
        $this->isCurrent = $isCurrent;
        $this->debtHistoryUpdateType = $debtHistoryUpdateType;
        $this->orderId = $orderId;
        $this->containerOrderId = $containerOrderId;
        $this->vatId = $vatId;
        $this->paymentId = $paymentId;
        $this->otherDebtId = $otherDebtId;
        $this->numberOfMoney = $numberOfMoney;
        $this->updateDate = $updateDate;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->version = $version;
    }

    /**
     * @return DebtHistoryId
     */
    public function getDebtHistoryId(): DebtHistoryId
    {
        return $this->debtHistoryId;
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
     * @return int
     */
    public function getTotalDebt(): int
    {
        return $this->totalDebt;
    }

    /**
     * @return int
     */
    public function getTotalPayment(): int
    {
        return $this->totalPayment;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    /**
     * @return DebtHistoryUpdateType
     */
    public function getDebtHistoryUpdateType(): DebtHistoryUpdateType
    {
        return $this->debtHistoryUpdateType;
    }

    /**
     * @return OrderId|null
     */
    public function getOrderId(): ?OrderId
    {
        return $this->orderId;
    }

    /**
     * @return ContainerOrderId|null
     */
    public function getContainerOrderId(): ?ContainerOrderId
    {
        return $this->containerOrderId;
    }

    /**
     * @return VatId|null
     */
    public function getVatId(): ?VatId
    {
        return $this->vatId;
    }

    /**
     * @return PaymentId|null
     */
    public function getPaymentId(): ?PaymentId
    {
        return $this->paymentId;
    }

    /**
     * @return OtherDebtId|null
     */
    public function getOtherDebtId(): ?OtherDebtId
    {
        return $this->otherDebtId;
    }

    /**
     * @return int
     */
    public function getNumberOfMoney(): int
    {
        return $this->numberOfMoney;
    }

    /**
     * @return int
     */
    public function getUpdateDate(): int
    {
        return $this->updateDate;
    }

    /**
     * @return MonetaryUnitType
     */
    public function getMonetaryUnitType(): MonetaryUnitType
    {
        return $this->monetaryUnitType;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
