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
     * @var string
     */
    public string $count;

    /**
     * @param string $orderProductId
     * @param string $orderId
     * @param string $productId
     * @param string $productAttributeValueId
     * @param string $productAttributePriceId
     * @param string $count
     */
    public function __construct(string $orderProductId, string $orderId, string $productId, string $productAttributeValueId, string $productAttributePriceId, string $count)
    {
        $this->orderProductId = $orderProductId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
    }
}
