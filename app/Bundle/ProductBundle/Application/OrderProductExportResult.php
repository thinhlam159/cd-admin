<?php

namespace App\Bundle\ProductBundle\Application;

class OrderProductExportResult
{
    /**
     * @var string
     */
    public string $orderProductId;

    /**
     * @var string
     */
    public string $orderId;

    /**
     * @var string
     */
    public string $productId;

    /**
     * @var string
     */
    public string $productAttributeValueId;

    /**
     * @var string
     */
    public string $productAttributePriceId;

    /**
     * @var int
     */
    public int $count;

    /**
     * @var string
     */
    public string $measureUnitType;

    /**
     * @var int
     */
    public int $weight;

    /**
     * @var int
     */
    public int $attributeDisplayIndex;

    /**
     * @var int
     */
    public int $productOrderCost;

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
    public string $productAttributeValueCode;

    /**
     * @var string
     */
    public string $noticePriceType;

    /**
     * @var int
     */
    public int $productAttributePrice;

    /**
     * @var int
     */
    public int $productAttributePriceStandard;

    /**
     * @var string|null
     */
    public ?string $noteName;

    /**
     * @param string $orderProductId
     * @param string $orderId
     * @param string $productId
     * @param string $productAttributeValueId
     * @param string $productAttributePriceId
     * @param int $count
     * @param string $measureUnitType
     * @param int $weight
     * @param int $attributeDisplayIndex
     * @param int $productOrderCost
     * @param string $productName
     * @param string $productCode
     * @param string $productAttributeValueCode
     * @param string $noticePriceType
     * @param int $productAttributePrice
     * @param int $productAttributePriceStandard
     * @param string|null $noteName
     */
    public function __construct(string $orderProductId, string $orderId, string $productId, string $productAttributeValueId, string $productAttributePriceId, int $count, string $measureUnitType, int $weight, int $attributeDisplayIndex, int $productOrderCost, string $productName, string $productCode, string $productAttributeValueCode, string $noticePriceType, int $productAttributePrice, int $productAttributePriceStandard, ?string $noteName)
    {
        $this->orderProductId = $orderProductId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
        $this->weight = $weight;
        $this->attributeDisplayIndex = $attributeDisplayIndex;
        $this->productOrderCost = $productOrderCost;
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->productAttributeValueCode = $productAttributeValueCode;
        $this->noticePriceType = $noticePriceType;
        $this->productAttributePrice = $productAttributePrice;
        $this->productAttributePriceStandard = $productAttributePriceStandard;
        $this->noteName = $noteName;
    }
}
