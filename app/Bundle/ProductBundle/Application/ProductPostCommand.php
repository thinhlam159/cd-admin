<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPostCommand
{
    public string $name;
    public int $price;
    public ?string $featureImagePath;
    public string $content;
    public string $userId;
    public string $categoryId;

    /**
     * @param string $name name
     * @param int $price price
     * @param string|null $featureImagePath featureImagePath
     * @param string $content content
     * @param string $userId userId
     * @param string $categoryId categoryId
     */
    public function __construct(string $name, int $price, ?string $featureImagePath, string $content, string $userId, string $categoryId)
    {
        $this->name = $name;
        $this->price = $price;
        $this->featureImagePath = $featureImagePath;
        $this->content = $content;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
    }
}
