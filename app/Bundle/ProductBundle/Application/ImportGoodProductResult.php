<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodProductResult
{
    /**
     * @var string
     */
    public string $importGoodProductId;

    /**
     * @var string
     */
    public string $productId;
    /**
     * @var string
     */
    public string $productName;
    /**
     * @var string
     */
    public string $productCode;
    /**
     * @var string
     */
    public string $productAttributeValueId;
    /**
     * @var string
     */
    public string $productAttributeValueName;
    /**
     * @var string
     */
    public string $productAttributeValueCode;
    /**
     * @var string
     */
    public string $importGoodPrice;
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
     * @param string $importGoodProductId
     * @param string $productId
     * @param string $productName
     * @param string $productCode
     * @param string $productAttributeValueId
     * @param string $productAttributeValueName
     * @param string $productAttributeValueCode
     * @param string $importGoodPrice
     * @param string $monetaryUnitType
     * @param int $count
     * @param string $measureUnitType
     */
    public function __construct(string $importGoodProductId, string $productId, string $productName, string $productCode, string $productAttributeValueId, string $productAttributeValueName, string $productAttributeValueCode, string $importGoodPrice, string $monetaryUnitType, int $count, string $measureUnitType)
    {
        $this->importGoodProductId = $importGoodProductId;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributeValueName = $productAttributeValueName;
        $this->productAttributeValueCode = $productAttributeValueCode;
        $this->importGoodPrice = $importGoodPrice;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
    }
}
