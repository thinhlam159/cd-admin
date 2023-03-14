<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
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
     * @param IPaymentRepository $paymentRepository
     */
    public function __construct(IPaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
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

        DB::beginTransaction();
        try {
            $updateResult = $this->paymentRepository->updateCancelStatus($payment);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new PaymentCancelPutResult($paymentId->__toString());
    }
}
