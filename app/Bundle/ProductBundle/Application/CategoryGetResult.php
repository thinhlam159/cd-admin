<?php

namespace App\Bundle\ProductBundle\Application;

class CategoryGetResult
{
    public string $categoryId;
    public string $name;
    public string $slug;
    public string $parentId;

    /**
     * @param string $categoryId
     * @param string $name
     * @param string $slug
     * @param string $parentId
     */
    public function __construct(string $categoryId, string $name, string $slug, string $parentId)
    {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->slug = $slug;
        $this->parentId = $parentId;
    }
}
