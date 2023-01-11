<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IPaymentRepository;
use App\Bundle\ProductBundle\Domain\Model\Payment;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use App\Models\Payment as ModelPayment;

class PaymentRepository implements IPaymentRepository
{
    /**
     * @inheritDoc
     */
    public function create(Payment $payment): ?PaymentId
    {
        $result = ModelPayment::create([
            'id' => $payment->getPaymentId()->asString(),
            'cost' => $payment->getCost(),
            'monetary_unit_type' => $payment->getMonetaryUnitType()->getType(),
            'comment' => $payment->getComment(),
            'customer_id' => $payment->getCustomerId()->asString(),
            'user_id' => $payment->getUserId()->asString(),
            'payment_date' => $payment->getDate(),
    	]);
        if (!$result) {
            return null;
        }

        return $payment->getPaymentId();
    }
}
