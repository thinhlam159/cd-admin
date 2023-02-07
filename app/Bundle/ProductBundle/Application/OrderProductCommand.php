<?php

namespace App\Bundle\ProductBundle\Application;

class OrderProductCommand
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
    public int $attributeDisplayIndex;

    /**
     * @var string|null
     */
    public ?string $productAttributePriceId;

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
     * @param string $productId
     * @param string $productAttributeValueId
     * @param int $attributeDisplayIndex
     * @param string|null $productAttributePriceId
     * @param int $count
     * @param string $measureUnitType
     * @param int $weight
     */
    public function __construct(string $productId, string $productAttributeValueId, int $attributeDisplayIndex, ?string $productAttributePriceId, int $count, string $measureUnitType, int $weight)
    {
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->attributeDisplayIndex = $attributeDisplayIndex;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
        $this->weight = $weight;
    }
}
