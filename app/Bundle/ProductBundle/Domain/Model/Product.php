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
     * @var string
     */
    private string $code;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\CategoryId
     */
    private CategoryId $categoryId;

    /**
     * @param ProductId $productId
     * @param string $name
     * @param string $code
     * @param string $description
     * @param CategoryId $categoryId
     */
    public function __construct(ProductId $productId, string $name, string $code, string $description, CategoryId $categoryId)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
