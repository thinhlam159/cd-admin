<?php

namespace App\Bundle\ProductBundle\Application;

class ExportGoodProductResult
{
    /**
     * @var string
     */
    public string $exportGoodProductId;

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
     * @var string|null
     */
    public ?string $productAttributeValueName;

    /**
     * @var string
     */
    public string $productAttributeValueCode;

    /**
     * @var int
     */
    public int $count;

    /**
     * @var string
     */
    public string $measureUnitType;

    /**
     * @param string $exportGoodProductId
     * @param string $productId
     * @param string $productName
     * @param string $productCode
     * @param string $productAttributeValueId
     * @param string|null $productAttributeValueName
     * @param string $productAttributeValueCode
     * @param int $count
     * @param string $measureUnitType
     */
    public function __construct(string $exportGoodProductId, string $productId, string $productName, string $productCode, string $productAttributeValueId, ?string $productAttributeValueName, string $productAttributeValueCode, int $count, string $measureUnitType)
    {
        $this->exportGoodProductId = $exportGoodProductId;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributeValueName = $productAttributeValueName;
        $this->productAttributeValueCode = $productAttributeValueCode;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
    }
}
