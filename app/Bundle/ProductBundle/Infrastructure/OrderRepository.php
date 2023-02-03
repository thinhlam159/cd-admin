<?php

namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\Order;
use App\Bundle\ProductBundle\Domain\Model\OrderCriteria;
use App\Bundle\ProductBundle\Domain\Model\OrderDeliveryStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderProduct;
use App\Bundle\ProductBundle\Domain\Model\OrderProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalCountCustomerOrderCriteria;
use App\Bundle\ProductBundle\Domain\Model\StatisticalProductSaleCriteria;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\Order as ModelOrder;
use App\Models\OrderProduct as ModelOrderProduct;

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

    /**
     * @inheritDoc
     */
    public function createOrderProducts(array $orderProducts): bool
    {
        foreach ($orderProducts as $orderProduct) {
            $result = ModelOrderProduct::create([
                'id' => $orderProduct->getOrderProductId()->asString(),
                'order_id' => $orderProduct->getOrderId()->asString(),
                'product_id' => $orderProduct->getProductId()->asString(),
                'product_attribute_value_id' => $orderProduct->getProductAttributeValueId()->asString(),
                'product_attribute_price_id' => $orderProduct->getProductAttributePriceId()->asString(),
                'attribute_display_index' => $orderProduct->getAttributeDisplayIndex(),
                'weight' => $orderProduct->getWeight(),
                'count' => $orderProduct->getCount(),
                'cost' => $orderProduct->getOrderProductCost(),
                'measure_unit_type' => $orderProduct->getMeasureUnitType()->getType(),
            ]);
            if (!$result) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function findAll(OrderCriteria $criteria): array
    {
        $entities = ModelOrder::paginate(PaginationConst::PAGINATE_ROW);
        $orders = [];
        foreach ($entities as $entity) {
            $order = new Order(
                new OrderId($entity->id),
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                OrderDeliveryStatus::fromStatus($entity->delivery_status),
                OrderPaymentStatus::fromStatus($entity->payment_status)
            );
            $order->setUpdatedAt(SettingDate::fromYmdHis($entity->updated_at));

            $orders[] = $order;
        }
        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$orders, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findOrderProductsByOrderId(OrderId $orderId): array
    {
        $entity = ModelOrder::find($orderId->asString());
        $orderProductEntities = $entity->orderProducts;
        $orderProducts = [];
        foreach ($orderProductEntities as $orderProductEntity) {
            $orderProducts[] = new OrderProduct(
                new OrderProductId($orderProductEntity->id),
                new OrderId($orderProductEntity->order_id),
                new ProductId($orderProductEntity->product_id),
                new ProductAttributeValueId($orderProductEntity->product_attribute_value_id),
                new ProductAttributePriceId($orderProductEntity->product_attribute_price_id),
                $orderProductEntity->count,
                MeasureUnitType::fromType($orderProductEntity->measure_unit_type),
                $orderProductEntity->attribute_display_index,
                $orderProductEntity->weight,
                $orderProductEntity->cost
            );
        }

        return $orderProducts;
    }

    /**
     * @inheritDoc
     */
    public function findById(OrderId $orderId): ?Order
    {
        $entity = ModelOrder::find($orderId->asString());

        if (!$entity) {
            return null;
        }
        $order = new Order(
            new OrderId($entity->id),
            new CustomerId($entity->customer_id),
            new UserId($entity->user_id),
            OrderDeliveryStatus::fromStatus($entity->delivery_status),
            OrderPaymentStatus::fromStatus($entity->payment_status)
        );
        $order->setUpdatedAt(SettingDate::fromYmdHis($entity->updated_at));
        $order->setCreatedAt(SettingDate::fromYmdHis($entity->created_at));

        return $order;
    }

    /**
     * @inheritDoc
     */
    public function updateDeliveryStatus(Order $order): bool
    {
        $entity = ModelOrder::find($order->getOrderId()->asString());
        $result = $entity::update([
            'delivery_status' => $order->getOrderDeliveryStatus()->getValue(),
            'payment_status' => $order->getOrderPaymentStatus()->getValue(),
        ]);
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function findAllByProductSale(StatisticalProductSaleCriteria $criteria): array
    {
        $conditions = [];
//        if (!is_null($criteria->getCategoryId())) {
//            $conditions[] = ['category_id', '=', $criteria->getCategoryId()];
//        }
        if (!is_null($criteria->getProductAttributeValueId())) {
            $conditions[] = ['product_attribute_value_id', '=', $criteria->getProductAttributeValueId()];
        }

        if (!is_null($criteria->getStartDate())) {
            $conditions[] = ['order_date', '>=', $criteria->getStartDate()];
            $conditions[] = ['order_date', '<=', $criteria->getEndDate()];
        }

        $entities = ModelOrder::where($conditions)->get();

        $orders = [];
        foreach ($entities as $entity) {
            $order = new Order(
                new OrderId($entity->id),
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                OrderDeliveryStatus::fromStatus($entity->delivery_status),
                OrderPaymentStatus::fromStatus($entity->payment_status),
            );
            $order->setOrderDate(SettingDate::fromYmdHis($entity->order_date));

            $orders[] = $order;
        }

        return $orders;
    }

    /**
     * @inheritDoc
     */
    public function countCustomerOrders(StatisticalCountCustomerOrderCriteria $criteria): int
    {
        $conditions = [];
        $conditions[] = ['customer_id', '=', $criteria->getCustomerId()->asString()];
        if (!is_null($criteria->getStartDate())) {
            $conditions[] = ['order_date', '>=', $criteria->getStartDate()->asString()];
            $conditions[] = ['order_date', '<=', $criteria->getEndDate()->asString()];
        }

        return ModelOrder::where($conditions)->count();
    }
}
