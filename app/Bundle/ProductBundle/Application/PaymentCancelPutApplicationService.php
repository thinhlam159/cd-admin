<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IPaymentRepository;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCancelPutApplicationService
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
     * @param PaymentCancelPutCommand $command
     * @return PaymentCancelPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(PaymentCancelPutCommand $command): PaymentCancelPutResult
    {
        $paymentId = new PaymentId($command->paymentId);
        $payment = $this->paymentRepository->findById($paymentId);
        $payment->updateCancelStatus();

        $debt = $this->debtHistoryRepository->findByPaymentId($paymentId);

        DB::beginTransaction();
        try {
            $updateResult = $this->paymentRepository->updateCancelStatus($payment);
            $result = $this->debtHistoryRepository->deleteById($debt->getDebtHistoryId());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new PaymentCancelPutResult($paymentId->__toString());
    }
}
