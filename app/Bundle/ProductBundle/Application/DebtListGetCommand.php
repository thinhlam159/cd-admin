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
     * @param string|null $customerId
     * @param string|null $keyword
     */
    public function __construct(?string $customerId, ?string $keyword)
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
    }
}
