<?php

namespace App\Bundle\ProductBundle\Application;

final class OrderByCustomerListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $keyword;

    /**
     * @var string|null
     */
    public ?string $customerId;

    /**
     * @param string|null $keyword
     * @param string|null $customerId
     */
    public function __construct(?string $keyword, ?string $customerId)
    {
        $this->keyword = $keyword;
        $this->customerId = $customerId;
    }
}
