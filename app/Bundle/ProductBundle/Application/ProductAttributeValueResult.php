<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValueResult
{
    public string $productAttributeValueId;
    public string $productId;
    public string $productAttributeName;
    public string $productAttributeValue;
    public string $nameByAttribute;
    public string $measureUnit;
    public string $productInventoryCount;
    public string $price;
    public string $monetaryUnit;

    /**
     * @param string $productAttributeValueId
     * @param string $productId
     * @param string $productAttributeName
     * @param string $productAttributeValue
     * @param string $nameByAttribute
     * @param string $measureUnit
     * @param string $productInventoryCount
     * @param string $price
     * @param string $monetaryUnit
     */
    public function __construct(string $productAttributeValueId, string $productId, string $productAttributeName, string $productAttributeValue, string $nameByAttribute, string $measureUnit, string $productInventoryCount, string $price, string $monetaryUnit)
    {
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productId = $productId;
        $this->productAttributeName = $productAttributeName;
        $this->productAttributeValue = $productAttributeValue;
        $this->nameByAttribute = $nameByAttribute;
        $this->measureUnit = $measureUnit;
        $this->productInventoryCount = $productInventoryCount;
        $this->price = $price;
        $this->monetaryUnit = $monetaryUnit;
    }
}
