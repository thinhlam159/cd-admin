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
     * @var SettingDate|null
     */
    private ?SettingDate $updatedAt;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $createdAt;

    /**
     * @var SettingDate
     */
    private SettingDate $orderDate;

    /**
     * @param OrderId $orderId
     * @param \App\Bundle\Admin\Domain\Model\CustomerId $customerId
     * @param \App\Bundle\Admin\Domain\Model\UserId $userId
     * @param OrderDeliveryStatus $orderDeliveryStatus
     * @param OrderPaymentStatus $orderPaymentStatus
     * @param SettingDate $orderDate
     */
    public function __construct(
        OrderId $orderId,
        CustomerId $customerId,
        UserId $userId,
        OrderDeliveryStatus $orderDeliveryStatus,
        OrderPaymentStatus $orderPaymentStatus,
        SettingDate $orderDate
    )
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->orderDeliveryStatus = $orderDeliveryStatus;
        $this->orderPaymentStatus = $orderPaymentStatus;
        $this->orderDate = $orderDate;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    /**
     * @return \App\Bundle\Admin\Domain\Model\CustomerId
     */
    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return \App\Bundle\Admin\Domain\Model\UserId
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

    /**
     * @return SettingDate|null
     */
    public function getUpdatedAt(): ?SettingDate
    {
        return $this->updateAt;
    }

    /**
     * @param SettingDate|null $updateAt
     */
    public function setUpdatedAt(?SettingDate $updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @return SettingDate|null
     */
    public function getCreatedAt(): ?SettingDate
    {
        return $this->createdAt;
    }

    /**
     * @param SettingDate|null $createdAt
     */
    public function setCreatedAt(?SettingDate $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return SettingDate|null
     */
    public function getOrderDate(): ?SettingDate
    {
        return $this->orderDate;
    }

    /**
     * @param SettingDate|null $orderDate
     */
    public function setOrderDate(?SettingDate $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    /**
     * @param OrderDeliveryStatus $orderDeliveryStatus
     * @return void
     * @throws \App\Bundle\Common\Domain\Model\InvalidArgumentException
     */
    public function updateDeliveryStatus(OrderDeliveryStatus $orderDeliveryStatus): void
    {
        if ($orderDeliveryStatus->getStatus() == OrderDeliveryStatus::SHIPPING && $this->orderDeliveryStatus == OrderDeliveryStatus::IN_PROGRESS) {
            $this->orderDeliveryStatus = OrderDeliveryStatus::fromStatus(OrderDeliveryStatus::SHIPPING);
            if ($this->orderPaymentStatus->getStatus() == OrderPaymentStatus::PLANNING) {
                $this->orderPaymentStatus = OrderPaymentStatus::fromStatus(OrderPaymentStatus::PENDING);
            }
        }
        if ($orderDeliveryStatus->getStatus() == OrderDeliveryStatus::DONE
            && ($this->orderDeliveryStatus == OrderDeliveryStatus::IN_PROGRESS || $this->orderDeliveryStatus == OrderDeliveryStatus::SHIPPING))
        {
            $this->orderDeliveryStatus = OrderDeliveryStatus::fromStatus(OrderDeliveryStatus::DONE);
            if ($this->orderPaymentStatus->getStatus() == OrderPaymentStatus::PLANNING) {
                $this->orderPaymentStatus = OrderPaymentStatus::fromStatus($orderDeliveryStatus->getStatus());
            }
        }
    }

    /**
     * @param OrderPaymentStatus $orderPaymentStatus
     * @return void
     * @throws \App\Bundle\Common\Domain\Model\InvalidArgumentException
     */
    public function updatePaymentStatus(OrderPaymentStatus $orderPaymentStatus): void
    {
        if ($orderPaymentStatus->getStatus() == OrderPaymentStatus::DONE
            && ($this->orderPaymentStatus->getStatus() == OrderPaymentStatus::PLANNING || $this->orderPaymentStatus->getStatus() == OrderPaymentStatus::PENDING)
        ) {
            $this->orderPaymentStatus = OrderPaymentStatus::fromStatus($orderPaymentStatus->getStatus());
        }
    }

    public function updateCancelStatus(): void
    {
        $this->orderDeliveryStatus = OrderDeliveryStatus::fromStatus(OrderDeliveryStatus::RETURNED_GOODS);
        $this->orderPaymentStatus = OrderPaymentStatus::fromStatus(OrderPaymentStatus::CANCEL);
    }
}
