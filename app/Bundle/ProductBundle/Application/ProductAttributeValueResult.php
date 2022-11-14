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
    public string $price;
    public string $monetaryUnit;
    public string $noticePriceType;

    /**
     * @param string $productAttributeValueId
     * @param string $productId
     * @param string $productAttributeName
     * @param string $productAttributeValue
     * @param string $code
     * @param string $measureUnit
     * @param string $productInventoryCount
     * @param string $price
     * @param string $monetaryUnit
     * @param string $noticePriceType
     */
    public function __construct(
        string $productAttributeValueId,
        string $productId,
        string $productAttributeName,
        string $productAttributeValue,
        string $code,
        string $measureUnit,
        string $productInventoryCount,
        string $price,
        string $monetaryUnit,
        string $noticePriceType
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
    }
}
