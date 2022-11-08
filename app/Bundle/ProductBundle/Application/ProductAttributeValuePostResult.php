<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValuePostResult
{
    /**
     * @var string
     */
    public string $productAttributeValueId;

    /**
     * @param string $productAttributeValueId
     */
    public function __construct(string $productAttributeValueId)
    {
        $this->productAttributeValueId = $productAttributeValueId;
    }
}
