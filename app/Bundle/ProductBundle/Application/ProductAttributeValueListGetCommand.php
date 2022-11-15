<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValueListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $productId;

    /**
     * @param string|null $productId
     */
    public function __construct(?string $productId)
    {
        $this->productId = $productId;
    }
}
