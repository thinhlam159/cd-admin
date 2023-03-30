<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;

class ContainerOrderCustomerListGetResult
{
    /**
     * @var PaginationResult
     */
    public PaginationResult $pagination;

    /**
     * @var ContainerOrderResult[]
     */
    public array $containerOrderResults;

    /**
     * @param PaginationResult $pagination
     * @param ContainerOrderResult[] $containerOrderResults
     */
    public function __construct(PaginationResult $pagination, array $containerOrderResults)
    {
        $this->pagination = $pagination;
        $this->containerOrderResults = $containerOrderResults;
    }
}
