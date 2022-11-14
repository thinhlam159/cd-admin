<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributePriceListPutCommand
{
    /**
     * @var ProductAttributePriceCommand[]
     */
    public array $productAttributePriceCommands;

    /**
     * @param ProductAttributePriceCommand[] $productAttributePriceCommands
     */
    public function __construct(array $productAttributePriceCommands)
    {
        $this->productAttributePriceCommands = $productAttributePriceCommands;
    }
}
