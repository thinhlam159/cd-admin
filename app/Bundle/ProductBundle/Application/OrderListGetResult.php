<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;

class OrderListGetResult
{
    /**
     * @var OrderResult[] $orderResults
     */
    public array $orderResults;

    /**
     * @var PaginationResult
     */
    public PaginationResult $paginationResult;

    /**
     * @param OrderResult[] $orderResults
     * @param PaginationResult $paginationResult
     */
    public function __construct(array $orderResults, PaginationResult $paginationResult)
    {
        $this->orderResults = $orderResults;
        $this->paginationResult = $paginationResult;
    }
}
