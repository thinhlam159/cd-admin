<?php

namespace App\Bundle\ProductBundle\Application;

class OrderGetResult
{
    /**
     * @var string
     */
    public string $orderId;

    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string
     */
    public string $customerName;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var string
     */
    public string $userName;

    /**
     * @var string
     */
    public string $deliveryStatus;

    /**
     * @var string
     */
    public string $paymentStatus;

    /**
     * @var string
     */
    public string $updatedAt;

    /**
     * @var OrderProductResult[]
     */
    public array $orderProductResults;

    /**
     * @param string $orderId
     * @param string $customerId
     * @param string $customerName
     * @param string $userId
     * @param string $userName
     * @param string $deliveryStatus
     * @param string $paymentStatus
     * @param string $updatedAt
     * @param OrderProductResult[] $orderProductResults
     */
    public function __construct(string $orderId, string $customerId, string $customerName, string $userId, string $userName, string $deliveryStatus, string $paymentStatus, string $updatedAt, array $orderProductResults)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->deliveryStatus = $deliveryStatus;
        $this->paymentStatus = $paymentStatus;
        $this->updatedAt = $updatedAt;
        $this->orderProductResults = $orderProductResults;
    }
}
