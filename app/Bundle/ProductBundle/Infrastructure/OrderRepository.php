<?php

namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\Order;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Models\Order as ModelOrder;

final class OrderRepository implements IOrderRepository
{
    /**
     * @inheritDoc
     */
    public function create(Order $order): ?OrderId
    {
        $result = ModelOrder::create([
            'id' => $order->getOrderId()->asString(),
            'customer_id' => $order->getCustomerId()->asString(),
            'user_id' => $order->getUserId()->asString(),
            'delivery_status' => $order->getOrderDeliveryStatus()->getStatus(),
            'payment_status' => $order->getOrderPaymentStatus()->getStatus(),
    	]);
        if (!$result) {
            return null;
        }

        return $order->getOrderId();
    }

}
