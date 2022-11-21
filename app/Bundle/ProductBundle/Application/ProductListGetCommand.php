<?php

namespace App\Bundle\ProductBundle\Application;

class ProductListGetCommand
{
    public array $productAttributeValueIds;

    /**
     * @param array $productAttributeValueIds
     */
    public function __construct(array $productAttributeValueIds)
    {
        $this->productAttributeValueIds = $productAttributeValueIds;
    }
}
