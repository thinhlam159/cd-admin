<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class Category
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\CategoryId
     */
    private CategoryId $categoryId;

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $slug;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\CategoryId
     */
    private CategoryId $parentId;

    /**
     * @param CategoryId $categoryId
     * @param string $name
     * @param string $slug
     * @param string $parentId
     */
    public function __construct(CategoryId $categoryId, string $name, string $slug, CategoryId $parentId)
    {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->slug = $slug;
        $this->parentId = $parentId;
    }

    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
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
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return CategoryId
     */
    public function getParentId(): CategoryId
    {
        return $this->parentId;
    }
}
