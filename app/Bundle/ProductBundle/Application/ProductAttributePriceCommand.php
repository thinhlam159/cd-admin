<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributePriceCommand
{
    /**
     * @var string
     */
    public string $productAttributePriceId;

    /**
     * @var string
     */
    public string $productAttributeValueId;

    /**
     * @var int
     */
    public int $price;

    /**
     * @var string
     */
    public string $noticePriceType;

    /**
     * @param string $productAttributePriceId
     * @param string $productAttributeValueId
     * @param int $price
     * @param string $noticePriceType
     */
    public function __construct(string $productAttributePriceId, string $productAttributeValueId, int $price, string $noticePriceType)
    {
        $this->productAttributePriceId = $productAttributePriceId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->noticePriceType = $noticePriceType;
    }
}
