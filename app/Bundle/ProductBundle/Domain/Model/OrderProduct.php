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
     * @var int
     */
    private int $attributeDisplayIndex;

    /**
     * @var int
     */
    private int $weight;

    /**
     * @param OrderProductId $orderProductId
     * @param OrderId $orderId
     * @param ProductId $productId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param ProductAttributePriceId $productAttributePriceId
     * @param int $count
     * @param int $attributeDisplayIndex
     * @param int $weight
     */
    public function __construct(
        OrderProductId $orderProductId,
        OrderId $orderId,
        ProductId $productId,
        ProductAttributeValueId $productAttributeValueId,
        ProductAttributePriceId $productAttributePriceId,
        int $count,
        int $attributeDisplayIndex,
        int $weight
    )
    {
        $this->orderProductId = $orderProductId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
        $this->attributeDisplayIndex = $attributeDisplayIndex;
        $this->weight = $weight;
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
}
