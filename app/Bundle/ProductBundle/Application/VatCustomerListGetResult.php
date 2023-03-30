<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class VatCustomerListGetResult
{
    /**
     * @var PaginationResult
     */
    public PaginationResult $pagination;

    /**
     * @var VatResult[]
     */
    public array $vatResults;

    /**
     * @param PaginationResult $pagination
     * @param VatResult[] $vatResults
     */
    public function __construct(PaginationResult $pagination, array $vatResults)
    {
        $this->pagination = $pagination;
        $this->vatResults = $vatResults;
    }
}
