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
     * @var string
     */
    public string $productAttributePriceId;
    /**
     * @var int
     */
    public int $count;

    /**
     * @param string $productId
     * @param string $productAttributeValueId
     * @param string $productAttributePriceId
     * @param int $count
     */
    public function __construct(string $productId, string $productAttributeValueId, string $productAttributePriceId, int $count)
    {
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productAttributePriceId = $productAttributePriceId;
        $this->count = $count;
    }
}
