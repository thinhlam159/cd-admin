<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalCustomerOrderAmountListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $customerId;

    /**
     * @var string|null
     */
    public ?string $keyword;

    /**
     * @var string|null
     */
    public ?string $order;

    /**
     * @var string|null
     */
    public ?string $sort;

    /**
     * @var string|null
     */
    public ?string $startDate;

    /**
     * @var string|null
     */
    public ?string $endDate;

    /**
     * @param string|null $customerId
     * @param string|null $keyword
     * @param string|null $order
     * @param string|null $sort
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(
        ?string $customerId,
        ?string $keyword,
        ?string $order,
        ?string $sort,
        ?string $startDate,
        ?string $endDate
    )
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
        $this->order = $order;
        $this->sort = $sort;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
