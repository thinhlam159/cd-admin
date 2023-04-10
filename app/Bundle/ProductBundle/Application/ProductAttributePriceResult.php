<?php

namespace App\Bundle\ProductBundle\Application;

final class ProductAttributePriceResult
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
    public string $monetaryUnitType;
    /**
     * @var string
     */
    public string $noticePriceType;
    /**
     * @var bool
     */
    public bool $isCurrent;
    /**
     * @var int
     */
    public int $standardPrice;

    /**
     * @param string $productAttributePriceId
     * @param string $productAttributeValueId
     * @param int $price
     * @param string $monetaryUnitType
     * @param string $noticePriceType
     * @param bool $isCurrent
     * @param int $standardPrice
     */
    public function __construct(string $productAttributePriceId, string $productAttributeValueId, int $price, string $monetaryUnitType, string $noticePriceType, bool $isCurrent, int $standardPrice)
    {
        $this->productAttributePriceId = $productAttributePriceId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->noticePriceType = $noticePriceType;
        $this->isCurrent = $isCurrent;
        $this->standardPrice = $standardPrice;
    }
}
