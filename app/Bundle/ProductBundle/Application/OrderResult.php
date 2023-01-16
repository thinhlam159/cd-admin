<?php

namespace App\Bundle\ProductBundle\Application;

class OrderResult
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
     * @var OrderProductResult[]
     */
    public array $orderProductResults;

    /**
     * @var string
     */
    public string $updatedAt;

    /**
     * @var string
     */
    public string $orderDate;

    /**
     * @var int
     */
    public int $totalCost;

    /**
     * @param string $orderId
     * @param string $customerId
     * @param string $customerName
     * @param string $userId
     * @param string $userName
     * @param string $deliveryStatus
     * @param string $paymentStatus
     * @param OrderProductResult[] $orderProductResults
     * @param string $updatedAt
     * @param string $orderDate
     * @param int $totalCost
     */
    public function __construct(string $orderId, string $customerId, string $customerName, string $userId, string $userName, string $deliveryStatus, string $paymentStatus, array $orderProductResults, string $updatedAt, string $orderDate, int $totalCost)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->deliveryStatus = $deliveryStatus;
        $this->paymentStatus = $paymentStatus;
        $this->orderProductResults = $orderProductResults;
        $this->updatedAt = $updatedAt;
        $this->orderDate = $orderDate;
        $this->totalCost = $totalCost;
    }
}
