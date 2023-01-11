<?php

namespace App\Bundle\ProductBundle\Application;

class CustomerDebtHistoryListGetCommand
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string|null
     */
    public ?string $keyword;

    /**
     * @param string $customerId
     * @param string|null $keyword
     */
    public function __construct(string $customerId, ?string $keyword)
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
    }
}
