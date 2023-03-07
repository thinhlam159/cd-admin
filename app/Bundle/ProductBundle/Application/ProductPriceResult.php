<?php

namespace App\Bundle\ProductBundle\Application;

final class ProductPriceResult
{
    /**
     * @var string
     */
    public string $productId;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $code;

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
     * @param string $productId
     * @param string $name
     * @param string $code
     * @param string $productAttributePriceId
     * @param string $productAttributeValueId
     * @param int $price
     * @param string $monetaryUnitType
     * @param string $noticePriceType
     * @param bool $isCurrent
     */
    public function __construct(string $productId, string $name, string $code, string $productAttributePriceId, string $productAttributeValueId, int $price, string $monetaryUnitType, string $noticePriceType, bool $isCurrent)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->code = $code;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->noticePriceType = $noticePriceType;
        $this->isCurrent = $isCurrent;
    }
}
