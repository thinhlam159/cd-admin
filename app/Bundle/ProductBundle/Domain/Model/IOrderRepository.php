<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IOrderRepository
{
    /**
     * @param Order $order
     * @return OrderId|null
     */
    public function create(Order $order): ?OrderId;

    /**
     * @param OrderProduct[] $orderProducts
     * @return bool
     */
    public function createOrderProducts(array $orderProducts): bool;

    /**
     * @param OrderCriteria $criteria
     * @return array{Order[], Pagination}
     */
    public function findAll(OrderCriteria $criteria): array;

    /**
     * @param OrderId $orderId
     * @return OrderProduct[]
     */
    public function findOrderProductsByOrderId(OrderId $orderId): array;

    /**
     * @param OrderId $orderId
     * @return Order|null
     */
    public function findById(OrderId $orderId): ?Order;

    /**
     * @param Order $order
     * @return bool
     */
    public function updateDeliveryStatus(Order $order): bool;

    /**
     * @param StatisticalProductSaleCriteria $criteria
     * @return Order[]
     */
    public function findAllByProductSale(StatisticalProductSaleCriteria $criteria): array;
}
