<?php

namespace App\Bundle\ProductBundle\Application;

final class PaymentStatusPutCommand
{
    /**
     * @var string
     */
    public string $orderId;

    /**
     * @var string
     */
    public string $paymentStatus;

    /**
     * @param string $orderId
     * @param string $paymentStatus
     */
    public function __construct(string $orderId, string $paymentStatus)
    {
        $this->orderId = $orderId;
        $this->paymentStatus = $paymentStatus;
    }
}
