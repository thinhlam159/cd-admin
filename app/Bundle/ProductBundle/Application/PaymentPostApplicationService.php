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
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentPostApplicationService
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
     * @param PaymentPostCommand $command
     * @return PaymentPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(PaymentPostCommand $command): PaymentPostResult
    {
        $paymentId = PaymentId::newId();
        $customerId = new CustomerId($command->customerId);
        $userId = new UserId($command->userId);
        $payment = new Payment(
            $paymentId,
            $command->cost,
            MonetaryUnitType::fromValue($command->monetaryUnitType),
            $command->comment,
            $customerId,
            $userId,
            $command->date
        );
        $currentDebt = $this->debtHistoryRepository->findCurrentDebtByCustomerId($customerId);

        $debtHistoryId = DebtHistoryId::newId();
        $newDebtHistory = new DebtHistory(
            $debtHistoryId,
            $customerId,
            $userId,
            !is_null($currentDebt) ? $currentDebt->getTotalDebt() : 0,
            !is_null($currentDebt) ? $currentDebt->getTotalPayment() + $command->cost : $command->cost,
            !is_null($currentDebt) ? $currentDebt->getRestDebt() - $command->cost : 0,
            true,
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::PAYMENT),
            null,
            null,
            null,
            $paymentId,
            null,
            $command->cost,
            SettingDate::fromTimeStamps($command->date),
            MonetaryUnitType::fromValue($command->monetaryUnitType),
            $command->comment,
            !is_null($currentDebt) ? $currentDebt->getVersion() + 1 : 1
        );

        DB::beginTransaction();
        try {
            $paymentId = $this->paymentRepository->create($payment);
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

        return new PaymentPostResult($paymentId->__toString());
    }
}
