<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\ContainerOrder;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrderId;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;
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
}
