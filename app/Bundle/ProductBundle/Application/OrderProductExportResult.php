<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;

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
     * @var string
     */
    public string $count;

    /**
     * @var string
     */
    public string $productAttributeValueName;

    /**
     * @var string
     */
    public string $productName;

    /**
     * @param string $orderProductId
     * @param string $orderId
     * @param string $productId
     * @param string $productAttributeValueId
     * @param string $productAttributePriceId
     * @param string $count
     * @param string $productAttributeValueName
     * @param string $productName
     */
    public function __construct(string $orderProductId, string $orderId, string $productId, string $productAttributeValueId, string $productAttributePriceId, string $count, string $productAttributeValueName, string $productName)
    {
        $this->orderProductId = $orderProductId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
        $this->productAttributeValueName = $productAttributeValueName;
        $this->productName = $productName;
    }
}
