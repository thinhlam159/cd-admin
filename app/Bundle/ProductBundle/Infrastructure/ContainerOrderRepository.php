<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\Pagination;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrder;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrderId;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Models\ContainerOrder as ModelContainerOrder;

class ContainerOrderRepository implements IContainerOrderRepository
{
    /**
     * @inheritDoc
     */
    public function create(ContainerOrder $containerOrder): ?ContainerOrderId
    {
        $result = ModelContainerOrder::create([
            'id' => $containerOrder->getContainerOrderId()->asString(),
            'cost' => $containerOrder->getCost(),
            'monetary_unit_type' => $containerOrder->getMonetaryUnitType()->getType(),
            'comment' => $containerOrder->getComment(),
            'customer_id' => $containerOrder->getCustomerId()->asString(),
            'user_id' => $containerOrder->getUserId()->asString(),
            'payment_status' => $containerOrder->getPaymentStatus()->getStatus(),
            'arising_date' => $containerOrder->getArisingDate()->asString(),
    	]);
        if (!$result) {
            return null;
        }

        return $containerOrder->getContainerOrderId();
    }

    /**
     * @inheritDoc
     */
    public function findAllByCustomerId(CustomerId $customerId): array
    {
        $entities = ModelContainerOrder::where([['customer_id', '=', $customerId->asString()],])->orderBy('created_at', 'DESC')->paginate();

        $payments  = [];
        foreach ($entities as $entity) {
            $payments[] = new ContainerOrder(
                new ContainerOrderId($entity->id),
                $entity->cost,
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                $entity->comment,
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                OrderPaymentStatus::fromStatus($entity->payment_status),
                SettingDate::fromYmdHis($entity->arising_date),
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$payments, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findById(ContainerOrderId $containerOrderId): ?ContainerOrder
    {
        $entity = ModelContainerOrder::find($containerOrderId->asString());

        if (!$entity) return null;

        return new ContainerOrder(
            new ContainerOrderId($entity->id),
            $entity->cost,
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            $entity->comment,
            new CustomerId($entity->customer_id),
            new UserId($entity->user_id),
            OrderPaymentStatus::fromStatus($entity->payment_status),
            SettingDate::fromYmdHis($entity->arising_date),
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteById(ContainerOrderId $containerOrderId): bool
    {
        $result = ModelContainerOrder::find($containerOrderId->asString())->delete();
        if (!$result) return false;

        return true;
    }
}
