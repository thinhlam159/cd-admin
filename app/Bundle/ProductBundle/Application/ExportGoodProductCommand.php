<?php

namespace App\Bundle\ProductBundle\Application;

class ExportGoodProductCommand
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
    public int $count;

    /**
     * @param string $productId
     * @param string $productAttributeValueId
     * @param int $count
     */
    public function __construct(string $productId, string $productAttributeValueId, int $count)
    {
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->count = $count;
    }
}
