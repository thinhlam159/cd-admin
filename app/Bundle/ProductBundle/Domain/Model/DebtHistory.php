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
    
}
