<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValueResult
{
    /**
     * @var string
     */
    public string $productAttributeValueId;

    /**
     * @var string
     */
    public string $productId;

    /**
     * @var string
     */
    public string $productAttributeName;

    /**
     * @var string
     */
    public string $productAttributeValue;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $measureUnit;

    /**
     * @var string
     */
    public string $productInventoryCount;

    /**
     * @var int
     */
    public int $price;

    /**
     * @var string
     */
    public string $monetaryUnit;

    /**
     * @var string
     */
    public string $noticePriceType;

    /**
     * @var string
     */
    public string $productAttributePriceId;

    /**
     * @var int
     */
    public int $standardPrice;

    /**
     * @var bool|null
     */
    public ?bool $isOriginal;

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
     * @param bool|null $isOriginal
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
        int $standardPrice,
        ?bool $isOriginal = null
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
        $this->isOriginal = $isOriginal;
    }
}
