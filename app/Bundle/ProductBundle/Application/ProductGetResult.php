<?php

namespace App\Bundle\ProductBundle\Application;

class ProductGetResult
{
    public string $productId;
    public string $name;
    public int $price;
    public ?string $featureImagePath;
    public string $content;
    public string $userId;
    public string $categoryId;

    /**
     * @param string $productId
     * @param string $name
     * @param int $price
     * @param string|null $featureImagePath
     * @param string $content
     * @param string $userId
     * @param string $categoryId
     */
    public function __construct(string $productId, string $name, int $price, ?string $featureImagePath, string $content, string $userId, string $categoryId)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
        $this->featureImagePath = $featureImagePath;
        $this->content = $content;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
    }
}
