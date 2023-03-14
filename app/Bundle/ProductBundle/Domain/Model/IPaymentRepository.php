<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\Pagination;

interface IPaymentRepository
{
    /**
     * @param Payment $payment
     * @return PaymentId|null
     */
    public function create(Payment $payment): ?PaymentId;

    /**
     * @param PaymentId $paymentId
     * @return Payment|null
     */
    public function findById(PaymentId $paymentId): ?Payment;

    /**
     * @param Payment $payment
     * @return bool
     */
    public function updateResolvedStatus(Payment $payment): bool;

    /**
     * @param Payment $payment
     * @return bool
     */
    public function updateCancelStatus(Payment $payment): bool;

    /**
     * @param CustomerId $customerId
     * @return array{Payment[], Pagination}
     */
    public function findAllByCustomerId(CustomerId $customerId): array;
}
