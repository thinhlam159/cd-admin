<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class PaymentCustomerListGetResult
{
    /**
     * @var PaginationResult
     */
    public PaginationResult $pagination;

    /**
     * @var PaymentResult[]
     */
    public array $paymentResults;

    /**
     * @param PaginationResult $pagination
     * @param PaymentResult[] $paymentResults
     */
    public function __construct(PaginationResult $pagination, array $paymentResults)
    {
        $this->pagination = $pagination;
        $this->paymentResults = $paymentResults;
    }
}
