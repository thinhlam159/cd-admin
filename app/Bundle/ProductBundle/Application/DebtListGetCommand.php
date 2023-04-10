<?php

namespace App\Bundle\ProductBundle\Application;

class DebtListGetCommand
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
     * @param string|null $customerId
     * @param string|null $keyword
     * @param string|null $order
     * @param string|null $sort
     */
    public function __construct(?string $customerId, ?string $keyword, ?string $order, ?string $sort)
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
        $this->order = $order;
        $this->sort = $sort;
    }
}
