<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\ProductBundle\Domain\Model\IPaymentRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\Payment;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use App\Bundle\ProductBundle\Domain\Model\PaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
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
            'payment_date' => $payment->getDate()->asString(),
            'payment_status' => $payment->getPaymentStatus()->getStatus()
    	]);
        if (!$result) {
            return null;
        }

        return $payment->getPaymentId();
    }

    /**
     * @inheritDoc
     */
    public function findById(PaymentId $paymentId): ?Payment
    {
        $entity = ModelPayment::find($paymentId->asString());
        if (!$entity) {
            return null;
        }

        return new Payment(
            $paymentId,
            $entity->cost,
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            $entity->comment,
            new CustomerId($entity->customer_id),
            new UserId($entity->user_id),
            SettingDate::fromYmdHis($entity->payment_date),
            PaymentStatus::fromStatus($entity->payment_status)
        );
    }

    /**
     * @inheritDoc
     */
    public function updateResolvedStatus(Payment $payment): bool
    {
        $entity = ModelPayment::find($payment->getPaymentId()->asString());
        $result = $entity->update([
            'payment_status' => $payment->getPaymentStatus()->getStatus(),
        ]);
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function updateCancelStatus(Payment $payment): bool
    {
        $entity = ModelPayment::find($payment->getPaymentId()->asString());
        $result = $entity->update([
            'payment_status' => $payment->getPaymentStatus()->getStatus(),
        ]);
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function findAllByCustomerId(CustomerId $customerId): array
    {
        $entities = ModelPayment::where([
            ['customer_id', '=', $customerId->asString()],
            ['payment_status', '!=', 3]
        ])->orderBy('created_at', 'DESC')->paginate();

        $payments  = [];
        foreach ($entities as $entity) {
            $payments[] = new Payment(
                new PaymentId($entity->id),
                $entity->cost,
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                $entity->comment,
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                SettingDate::fromYmdHis($entity->payment_date),
                PaymentStatus::fromStatus($entity->payment_status)
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$payments, $pagination];
    }
}
