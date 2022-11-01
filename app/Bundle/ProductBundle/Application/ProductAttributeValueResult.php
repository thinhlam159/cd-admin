<?php

namespace App\Bundle\ProductBundle\Application;

class ProductAttributeValueResult
{
    public string $productAttributeValueId;
    public string $productId;
    public string $productAttributeId;
    public string $code;
    public string $description;
    public string $categoryId;
    public string $categoryName;

    /**
     * @param string $productId
     * @param string $name
     * @param string $code
     * @param string $description
     * @param string $categoryId
     */
    public function __construct(
        string $productId,
        string $name,
        string $code,
        string $description,
        string $categoryId,
        string $categoryName,
    )
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }
}
