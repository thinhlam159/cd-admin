<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValueResult
{
    public string $productAttributeValueId;
    public string $productId;
    public string $productAttributeName;
    public string $productAttributeValue;
    public string $code;
    public string $measureUnit;
    public string $productInventoryCount;
    public int $price;
    public string $monetaryUnit;
    public string $noticePriceType;
    public string $productAttributePriceId;
    public int $standardPrice;

    /**
     * @param string $productAttributeValueId
     * @param string $productId
     * @param string $productAttributeName
     * @param string $productAttributeValue
     * @param string $code
     * @param string $measureUnit
     * @param string $productInventoryCount
     * @param int $price
     * @param string $monetaryUnit
     * @param string $noticePriceType
     * @param string $productAttributePriceId
     * @param int $standardPrice
     */
    public function __construct(
        string $productAttributeValueId,
        string $productId,
        string $productAttributeName,
        string $productAttributeValue,
        string $code,
        string $measureUnit,
        string $productInventoryCount,
        int $price,
        string $monetaryUnit,
        string $noticePriceType,
        string $productAttributePriceId,
        int $standardPrice
    )
    {
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productId = $productId;
        $this->productAttributeName = $productAttributeName;
        $this->productAttributeValue = $productAttributeValue;
        $this->code = $code;
        $this->measureUnit = $measureUnit;
        $this->productInventoryCount = $productInventoryCount;
        $this->price = $price;
        $this->monetaryUnit = $monetaryUnit;
        $this->noticePriceType = $noticePriceType;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->standardPrice = $standardPrice;
    }
}
