<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValuePostCommand
{
    public string $productId;
    public string $productAttributeId;
    public string $measureUnitId;
    public string $value;
    public string $code;
    public int $price;
    public int $count;

    /**
     * @param string $productId
     * @param string $productAttributeId
     * @param string $measureUnitId
     * @param string $value
     * @param string $code
     * @param int $price
     * @param int $count
     */
    public function __construct(string $productId, string $productAttributeId, string $measureUnitId, string $value, string $code, int $price, int $count)
    {
        $this->productId = $productId;
        $this->productAttributeId = $productAttributeId;
        $this->measureUnitId = $measureUnitId;
        $this->value = $value;
        $this->code = $code;
        $this->price = $price;
        $this->count = $count;
    }
}
