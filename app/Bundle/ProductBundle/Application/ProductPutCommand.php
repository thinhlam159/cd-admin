<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPutCommand
{
    public string $productId;
    public string $name;
    public string $code;
    public string $description;
    public int $price;
    public string $monetaryUnitId;
    public string $categoryId;
    public string $measureUnitId;
    public string $productAttributeId;
    public string $productAttributeValue;
    public string $productAttributeCode;
    public string $path;
    public bool $isAvatar;

    /**
     * @param string $productId
     * @param string $name
     * @param string $code
     * @param string $description
     * @param int $price
     * @param string $monetaryUnitId
     * @param string $categoryId
     * @param string $measureUnitId
     * @param string $productAttributeId
     * @param string $productAttributeValue
     * @param string $productAttributeCode
     * @param string $path
     * @param bool $isAvatar
     */
    public function __construct(string $productId, string $name, string $code, string $description, int $price, string $monetaryUnitId, string $categoryId, string $measureUnitId, string $productAttributeId, string $productAttributeValue, string $productAttributeCode, string $path, bool $isAvatar)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->price = $price;
        $this->monetaryUnitId = $monetaryUnitId;
        $this->categoryId = $categoryId;
        $this->measureUnitId = $measureUnitId;
        $this->productAttributeId = $productAttributeId;
        $this->productAttributeValue = $productAttributeValue;
        $this->productAttributeCode = $productAttributeCode;
        $this->path = $path;
        $this->isAvatar = $isAvatar;
    }

}
