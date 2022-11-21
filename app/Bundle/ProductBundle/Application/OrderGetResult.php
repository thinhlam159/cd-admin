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
    public string $userId;

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
    public string $updateAt;

    /**
     * @param string $orderId
     * @param string $customerId
     * @param string $userId
     * @param string $deliveryStatus
     * @param string $paymentStatus
     * @param OrderProductResult[] $orderProductResults
     * @param string $updateAt
     */
    public function __construct(string $orderId, string $customerId, string $userId, string $deliveryStatus, string $paymentStatus, array $orderProductResults,string $updateAt)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->deliveryStatus = $deliveryStatus;
        $this->paymentStatus = $paymentStatus;
        $this->orderProductResults = $orderProductResults;
        $this->updateAt = $updateAt;
    }
}
