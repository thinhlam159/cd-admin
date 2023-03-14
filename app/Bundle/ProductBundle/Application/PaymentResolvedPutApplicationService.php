<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IPaymentRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\Payment;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use App\Bundle\ProductBundle\Domain\Model\PaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentResolvedPutApplicationService
{
    /**
     * @var IPaymentRepository
     */
    private IPaymentRepository $paymentRepository;

    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param IPaymentRepository $paymentRepository
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(
        IPaymentRepository $paymentRepository,
        IDebtHistoryRepository $debtHistoryRepository
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->debtHistoryRepository = $debtHistoryRepository;
    }

    /**
     * @param PaymentResolvedPutCommand $command
     * @return PaymentResolvedPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(PaymentResolvedPutCommand $command): PaymentResolvedPutResult
    {
        $paymentId = new PaymentId($command->paymentId);
        $payment = $this->paymentRepository->findById($paymentId);
        $customerId = $payment->getCustomerId();
        $userId = $payment->getUserId();
        $payment->updateResolvedStatus();
        $currentDebt = $this->debtHistoryRepository->findCurrentDebtByCustomerId($payment->getCustomerId());

        $debtHistoryId = DebtHistoryId::newId();
        $newDebtHistory = new DebtHistory(
            $debtHistoryId,
            $customerId,
            $userId,
            !is_null($currentDebt) ? $currentDebt->getTotalDebt() : 0,
            !is_null($currentDebt) ? $currentDebt->getTotalPayment() + $payment->getCost() : $payment->getCost(),
            !is_null($currentDebt) ? $currentDebt->getRestDebt() - $payment->getCost() : 0,
            true,
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::PAYMENT),
            null,
            null,
            null,
            $paymentId,
            null,
            $payment->getCost(),
            $payment->getDate(),
            $payment->getMonetaryUnitType(),
            $payment->getComment(),
            !is_null($currentDebt) ? $currentDebt->getVersion() + 1 : 1
        );

        DB::beginTransaction();
        try {
            $updateResult = $this->paymentRepository->updateResolvedStatus($payment);
            if ($currentDebt) {
                $this->debtHistoryRepository->updateCurrentDebtHistory($currentDebt->getDebtHistoryId());
            }
            $this->debtHistoryRepository->create($newDebtHistory);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new PaymentResolvedPutResult($paymentId->__toString());
    }
}
