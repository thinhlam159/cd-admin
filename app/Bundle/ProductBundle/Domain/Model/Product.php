<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class Product
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductId
     */
    private ProductId $productId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $price;

    /**
     * @var string|null
     */
    private string $featureImagePath;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\UserId
     */
    private UserId $userId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\CategoryId
     */
    private CategoryId $categoryId;

    /**
     * @param ProductId $productId
     * @param string $name
     * @param int $price
     * @param ?string $featureImagePath
     * @param string $content
     * @param UserId $userId
     * @param CategoryId $categoryId
     */
    public function __construct(ProductId $productId, string $name, int $price, ?string $featureImagePath, string $content, UserId $userId, CategoryId $categoryId)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
        $this->featureImagePath = $featureImagePath;
        $this->content = $content;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getFeatureImagePath(): ?string
    {
        return $this->featureImagePath;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
