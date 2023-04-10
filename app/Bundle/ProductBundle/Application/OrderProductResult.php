<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;

class OrderProductResult
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
     * @var string
     */
    public string $noticePriceType;

    /**
     * @var int
     */
    public int $price;

    /**
     * @var int
     */
    public int $cost;

    /**
     * @var string
     */
    public string $productAttributeValueCode;

    /**
     * @var string
     */
    public string $productName;

    /**
     * @var string
     */
    public string $productCode;

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
     * @param string $noticePriceType
     * @param int $price
     * @param int $cost
     * @param string $productAttributeValueCode
     * @param string $productName
     * @param string $productCode
     * @param string|null $noteName
     */
    public function __construct(string $orderProductId, string $orderId, string $productId, string $productAttributeValueId, string $productAttributePriceId, int $count, string $measureUnitType, int $weight, int $attributeDisplayIndex, string $noticePriceType, int $price, int $cost, string $productAttributeValueCode, string $productName, string $productCode, ?string $noteName)
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
        $this->noticePriceType = $noticePriceType;
        $this->price = $price;
        $this->cost = $cost;
        $this->productAttributeValueCode = $productAttributeValueCode;
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->noteName = $noteName;
    }
}
