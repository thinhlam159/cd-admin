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
     * @var string
     */
    public string $productId;

    /**
     * @param string $productAttributePriceId
     * @param string $productAttributeValueId
     * @param int $price
     * @param string $noticePriceType
     * @param string $productId
     */
    public function __construct(string $productAttributePriceId, string $productAttributeValueId, int $price, string $noticePriceType, string $productId)
    {
        $this->productAttributePriceId = $productAttributePriceId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->noticePriceType = $noticePriceType;
        $this->productId = $productId;
    }
}
