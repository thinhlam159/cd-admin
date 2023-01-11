<?php

namespace App\Bundle\ProductBundle\Application;

class DebtResult
{
    /**
     * @var string
     */
    public string $debtHistoryId;

    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string
     */
    public string $customerName;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var string
     */
    public string $userName;

    /**
     * @var int
     */
    public int $totalDebt;

    /**
     * @var int
     */
    public int $totalPayment;

    /**
     * @var bool
     */
    public bool $isCurrent;

    /**
     * @var string
     */
    public string $debtHistoryUpdateType;

    /**
     * @var string|null
     */
    public ?string $orderId;

    /**
     * @var string|null
     */
    public ?string $containerOrderId;

    /**
     * @var string|null
     */
    public ?string $vatId;

    /**
     * @var string|null
     */
    public ?string $paymentId;

    /**
     * @var string|null
     */
    public ?string $otherDebtId;

    /**
     * @var int
     */
    public int $numberOfMoney;

    /**
     * @var int
     */
    public int $updateDate;

    /**
     * @var string
     */
    public string $monetaryUnitType;

    /**
     * @var string|null
     */
    public ?string $comment;

    /**
     * @var int
     */
    public int $version;

    /**
     * @param string $debtHistoryId
     * @param string $customerId
     * @param string $customerName
     * @param string $userId
     * @param string $userName
     * @param int $totalDebt
     * @param int $totalPayment
     * @param bool $isCurrent
     * @param string $debtHistoryUpdateType
     * @param string|null $orderId
     * @param string|null $containerOrderId
     * @param string|null $vatId
     * @param string|null $paymentId
     * @param string|null $otherDebtId
     * @param int $numberOfMoney
     * @param int $updateDate
     * @param string $monetaryUnitType
     * @param string|null $comment
     * @param int $version
     */
    public function __construct(string $debtHistoryId, string $customerId, string $customerName, string $userId, string $userName, int $totalDebt, int $totalPayment, bool $isCurrent, string $debtHistoryUpdateType, ?string $orderId, ?string $containerOrderId, ?string $vatId, ?string $paymentId, ?string $otherDebtId, int $numberOfMoney, int $updateDate, string $monetaryUnitType, ?string $comment, int $version)
    {
        $this->debtHistoryId = $debtHistoryId;
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->userId = $userId;
        $this->userName = $userName;
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
        $this->comment = $comment;
        $this->version = $version;
    }
}
