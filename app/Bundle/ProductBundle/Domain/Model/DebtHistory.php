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
     * @var int
     */
    private int $restDebt;

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
     * @var SettingDate
     */
    private SettingDate $updatedDate;

    /**
     * @var MonetaryUnitType
     */
    private MonetaryUnitType $monetaryUnitType;

    /**
     * @var string|null
     */
    private ?string $comment;

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
     * @param int $restDebt
     * @param bool $isCurrent
     * @param DebtHistoryUpdateType $debtHistoryUpdateType
     * @param OrderId|null $orderId
     * @param ContainerOrderId|null $containerOrderId
     * @param VatId|null $vatId
     * @param PaymentId|null $paymentId
     * @param OtherDebtId|null $otherDebtId
     * @param int $numberOfMoney
     * @param SettingDate $updatedDate
     * @param MonetaryUnitType $monetaryUnitType
     * @param string|null $comment
     * @param int $version
     */
    public function __construct(DebtHistoryId $debtHistoryId, CustomerId $customerId, UserId $userId, int $totalDebt, int $totalPayment, int $restDebt, bool $isCurrent, DebtHistoryUpdateType $debtHistoryUpdateType, ?OrderId $orderId, ?ContainerOrderId $containerOrderId, ?VatId $vatId, ?PaymentId $paymentId, ?OtherDebtId $otherDebtId, int $numberOfMoney, SettingDate $updatedDate, MonetaryUnitType $monetaryUnitType, ?string $comment, int $version)
    {
        $this->debtHistoryId = $debtHistoryId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->totalDebt = $totalDebt;
        $this->totalPayment = $totalPayment;
        $this->restDebt = $restDebt;
        $this->isCurrent = $isCurrent;
        $this->debtHistoryUpdateType = $debtHistoryUpdateType;
        $this->orderId = $orderId;
        $this->containerOrderId = $containerOrderId;
        $this->vatId = $vatId;
        $this->paymentId = $paymentId;
        $this->otherDebtId = $otherDebtId;
        $this->numberOfMoney = $numberOfMoney;
        $this->updatedDate = $updatedDate;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->comment = $comment;
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
     * @return int
     */
    public function getRestDebt(): int
    {
        return $this->restDebt;
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
     * @return SettingDate
     */
    public function getUpdateDate(): SettingDate
    {
        return $this->updatedDate;
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
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $oldTotal
     * @return int
     */
    public function calculateTotalDebt(int $oldTotal): int
    {
        if ($this->debtHistoryUpdateType->getType() !== DebtHistoryUpdateType::PAYMENT) {
            $oldTotal += $this->numberOfMoney;
        }

        return $oldTotal;
    }

    /**
     * @param int $oldTotal
     * @return int
     */
    public function calculateTotalPayment(int $oldTotal): int
    {
        if ($this->debtHistoryUpdateType->getType() === DebtHistoryUpdateType::PAYMENT) {
            $oldTotal += $this->numberOfMoney;
        }

        return $oldTotal;
    }
}
