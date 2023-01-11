<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IPaymentRepository
{
    /**
     * @param Payment $payment
     * @return PaymentId|null
     */
    public function create(Payment $payment): ?PaymentId;
}
