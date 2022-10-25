<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPutResult
{
    /**
     * @var string
     */
    public string $productId;

    /**
     * @param string $productId
     */
    public function __construct(string $productId)
    {
        $this->productId = $productId;
    }
}
