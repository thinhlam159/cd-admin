<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeResult
{
    /**
     * @var string
     */
    public string $productAttributeId;
    /**
     * @var string
     */
    public string $name;

    /**
     * @param string $productAttributeId
     * @param string $name
     */
    public function __construct(string $productAttributeId, string $name)
    {
        $this->productAttributeId = $productAttributeId;
        $this->name = $name;
    }
}
