<?php

namespace App\Bundle\ProductBundle\Application;

class ProductResult
{
    /**
     * @var string
     */
    public string $productId;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var string
     */
    public string $categoryId;

    /**
     * @var string
     */
    public string $categoryName;

    /**
     * @var ProductAttributeValueResult[]
     */
    public array $productAttributeValueResults;

    /**
     * @param string $productId
     * @param string $name
     * @param string $code
     * @param string $description
     * @param string $categoryId
     * @param string $categoryName
     * @param ProductAttributeValueResult[] $productAttributeValueResults
     */
    public function __construct(string $productId, string $name, string $code, string $description, string $categoryId, string $categoryName, array $productAttributeValueResults)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->productAttributeValueResults = $productAttributeValueResults;
    }
}
