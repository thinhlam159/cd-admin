<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodProductCommand
{
    /**
     * @var string
     */
    public string $productId;

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
     * @var int
     */
    public int $count;

    /**
     * @var string
     */
    public string $measureUnitType;

    /**
     * @param string $productId
     * @param string $productAttributeValueId
     * @param int $price
     * @param string $monetaryUnitType
     * @param int $count
     * @param string $measureUnitType
     */
    public function __construct(string $productId, string $productAttributeValueId, int $price, string $monetaryUnitType, int $count, string $measureUnitType)
    {
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
    }
}
