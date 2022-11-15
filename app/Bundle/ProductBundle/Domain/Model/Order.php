<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\UserId;

final class Order
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\OrderId
     */
    private OrderId $orderId;

    /**
     * @var \App\Bundle\Admin\Domain\Model\CustomerId
     */
    private CustomerId $customerId;

    /**
     * @var \App\Bundle\Admin\Domain\Model\UserId
     */
    private UserId $userId;

    /**
     * @var OrderDeliveryStatus
     */
    private OrderDeliveryStatus $orderDeliveryStatus;

    /**
     * @var OrderPaymentStatus
     */
    private OrderPaymentStatus $orderPaymentStatus;

    /**
     * @param OrderId $orderId
     * @param CustomerId $customerId
     * @param UserId $userId
     * @param OrderDeliveryStatus $orderDeliveryStatus
     * @param OrderPaymentStatus $orderPaymentStatus
     */
    public function __construct(
        OrderId $orderId,
        CustomerId $customerId,
        UserId $userId,
        OrderDeliveryStatus $orderDeliveryStatus,
        OrderPaymentStatus $orderPaymentStatus
    )
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->orderDeliveryStatus = $orderDeliveryStatus;
        $this->orderPaymentStatus = $orderPaymentStatus;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    /**
     * @return CustomerId
     */
    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return OrderDeliveryStatus
     */
    public function getOrderDeliveryStatus(): OrderDeliveryStatus
    {
        return $this->orderDeliveryStatus;
    }

    /**
     * @return OrderPaymentStatus
     */
    public function getOrderPaymentStatus(): OrderPaymentStatus
    {
        return $this->orderPaymentStatus;
    }
}
