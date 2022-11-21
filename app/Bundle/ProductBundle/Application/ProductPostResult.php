<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPostResult
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
