<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\UserId;

final class OrderProduct
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\OrderProductId
     */
    private OrderProductId $orderProductId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\OrderId
     */
    private OrderId $orderId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductId
     */
    private ProductId $productId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId
     */
    private ProductAttributeValueId $productAttributeValueId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId
     */
    private ProductAttributePriceId $productAttributePriceId;

    /**
     * @var int
     */
    private int $count;

    /**
     * @var MeasureUnitType
     */
    private MeasureUnitType $measureUnitType;

    /**
     * @var int
     */
    private int $attributeDisplayIndex;

    /**
     * @var int
     */
    private int $weight;

    /**
     * @var int
     */
    private int $orderProductCost;

    /**
     * @param OrderProductId $orderProductId
     * @param OrderId $orderId
     * @param ProductId $productId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param ProductAttributePriceId $productAttributePriceId
     * @param int $count
     * @param MeasureUnitType $measureUnitType
     * @param int $attributeDisplayIndex
     * @param int $weight
     * @param int $orderProductCost
     */
    public function __construct(
        OrderProductId $orderProductId,
        OrderId $orderId,
        ProductId $productId,
        ProductAttributeValueId $productAttributeValueId,
        ProductAttributePriceId $productAttributePriceId,
        int $count,
        MeasureUnitType $measureUnitType,
        int $attributeDisplayIndex,
        int $weight,
        int $orderProductCost
    )
    {
        $this->orderProductId = $orderProductId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
        $this->attributeDisplayIndex = $attributeDisplayIndex;
        $this->weight = $weight;
        $this->orderProductCost = $orderProductCost;
    }

    /**
     * @return OrderProductId
     */
    public function getOrderProductId(): OrderProductId
    {
        return $this->orderProductId;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return ProductAttributeValueId
     */
    public function getProductAttributeValueId(): ProductAttributeValueId
    {
        return $this->productAttributeValueId;
    }

    /**
     * @return ProductAttributePriceId
     */
    public function getProductAttributePriceId(): ProductAttributePriceId
    {
        return $this->productAttributePriceId;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getAttributeDisplayIndex(): int
    {
        return $this->attributeDisplayIndex;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return MeasureUnitType
     */
    public function getMeasureUnitType(): MeasureUnitType
    {
        return $this->measureUnitType;
    }

    /**
     * @return int
     */
    public function getOrderProductCost(): int
    {
        return $this->orderProductCost;
    }
}
