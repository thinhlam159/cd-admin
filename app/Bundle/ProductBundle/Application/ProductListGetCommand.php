<?php

namespace App\Bundle\ProductBundle\Application;

class ProductListGetCommand
{
    /**
     * @var array
     */
    public array $productAttributeValueIds;

    /**
     * @var string|null
     */
    public ?string $keyword;

    /**
     * @param array $productAttributeValueIds
     * @param string|null $keyword
     */
    public function __construct(array $productAttributeValueIds, ?string $keyword)
    {
        $this->productAttributeValueIds = $productAttributeValueIds;
        $this->keyword = $keyword;
    }
}
