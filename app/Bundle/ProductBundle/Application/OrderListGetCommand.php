<?php

namespace App\Bundle\ProductBundle\Application;

final class OrderListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $keyword;

    /**
     * @param string|null $keyword
     */
    public function __construct(?string $keyword)
    {
        $this->keyword = $keyword;
    }
}
