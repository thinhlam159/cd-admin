<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\IPaymentRepository;

class PaymentCustomerListGetApplicationService
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
     * @param PaymentCustomerListGetCommand $command
     * @return PaymentCustomerListGetResult
     * @throws InvalidArgumentException
     */
    public function handle(PaymentCustomerListGetCommand $command): PaymentCustomerListGetResult
    {
        $customerId = new CustomerId($command->customerId);
        [$payments, $pagination] = $this->paymentRepository->findAllByCustomerId($customerId);
        $paymentResults = [];
        foreach ($payments as $payment) {
            $paymentResults[] = new PaymentResult(
                $payment->getPaymentId()->asString(),
                $payment->getCost(),
                $payment->getMonetaryUnitType()->getValue(),
                $payment->getComment(),
                $payment->getCustomerId()->asString(),
                $payment->getUserId()->asString(),
                $payment->getDate(),
                $payment->getPaymentStatus()->getValue(),
            );
        }

        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new PaymentCustomerListGetResult($paginationResult, $paymentResults);
    }
}
