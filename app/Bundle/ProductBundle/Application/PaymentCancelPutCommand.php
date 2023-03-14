<?php

namespace App\Bundle\ProductBundle\Application;

class PaymentCancelPutCommand
{
    /**
     * @var string
     */
    public string $paymentId;

    /**
     * @param string $paymentId
     */
    public function __construct(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }
}
