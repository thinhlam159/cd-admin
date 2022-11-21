<?php

namespace App\Bundle\ProductBundle\Application;

class ProductResult
{
    public string $productId;
    public string $name;
    public string $code;
    public string $description;
    public string $categoryId;
    public string $categoryName;
    public string $imagePath;
    public array $productAttributeValueResults;

    /**
     * @param string $productId
     * @param string $name
     * @param string $code
     * @param string $description
     * @param string $categoryId
     * @param string $categoryName
     * @param string $imagePath
     * @param ProductAttributeValueResult[] $productAttributeValueResults
     */
    public function __construct(string $productId, string $name, string $code, string $description, string $categoryId, string $categoryName, string $imagePath, array $productAttributeValueResults)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->imagePath = $imagePath;
        $this->productAttributeValueResults = $productAttributeValueResults;
    }
}
