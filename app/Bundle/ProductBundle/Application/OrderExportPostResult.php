<?php

namespace App\Bundle\ProductBundle\Application;

class OrderExportPostResult
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
     * @var OrderProductExportResult[]
     */
    public array $orderProductExportResults;

    /**
     * @var string
     */
    public string $updateAt;

    /**
     * @var string
     */
    public string $createdAt;

    /**
     * @var string
     */
    public string $customerName;

    /**
     * @param string $orderId
     * @param string $customerId
     * @param string $userId
     * @param string $deliveryStatus
     * @param string $paymentStatus
     * @param OrderProductExportResult[] $orderProductExportResults
     * @param string $updateAt
     * @param string $createdAt
     * @param string $customerName
     */
    public function __construct(string $orderId, string $customerId, string $userId, string $deliveryStatus, string $paymentStatus, array $orderProductExportResults,string $updateAt, string $createdAt, string $customerName)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->deliveryStatus = $deliveryStatus;
        $this->paymentStatus = $paymentStatus;
        $this->orderProductExportResults = $orderProductExportResults;
        $this->updateAt = $updateAt;
        $this->createdAt = $createdAt;
        $this->customerName = $customerName;
    }
}
