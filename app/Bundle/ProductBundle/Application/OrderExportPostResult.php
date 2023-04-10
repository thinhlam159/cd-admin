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
    public string $orderDate;

    /**
     * @var string
     */
    public string $customerName;

    /**
     * @var string|null
     */
    public ?string $customerPhone;

    /**
     * @var string|null
     */
    public ?string $customerAddress;

    /**
     * @var int
     */
    public int $totalCost;

    /**
     * @param string $orderId
     * @param string $customerId
     * @param string $userId
     * @param string $deliveryStatus
     * @param string $paymentStatus
     * @param OrderProductExportResult[] $orderProductExportResults
     * @param string $updateAt
     * @param string $createdAt
     * @param string $orderDate
     * @param string $customerName
     * @param string|null $customerPhone
     * @param string|null $customerAddress
     * @param int $totalCost
     */
    public function __construct(string $orderId, string $customerId, string $userId, string $deliveryStatus, string $paymentStatus, array $orderProductExportResults, string $updateAt, string $createdAt, string $orderDate, string $customerName, ?string $customerPhone, ?string $customerAddress, int $totalCost)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->deliveryStatus = $deliveryStatus;
        $this->paymentStatus = $paymentStatus;
        $this->orderProductExportResults = $orderProductExportResults;
        $this->updateAt = $updateAt;
        $this->createdAt = $createdAt;
        $this->orderDate = $orderDate;
        $this->customerName = $customerName;
        $this->customerPhone = $customerPhone;
        $this->customerAddress = $customerAddress;
        $this->totalCost = $totalCost;
    }
}
