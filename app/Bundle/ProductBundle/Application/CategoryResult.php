<?php

namespace App\Bundle\ProductBundle\Application;

class CategoryResult
{
    public string $categoryId;
    public string $name;
    public string $slug;
    public string $parentId;

    /**
     * @param string $categoryId categoryId
     * @param string $name name
     * @param string $slug slug
     * @param string $parentId parentId
     */
    public function __construct(string $categoryId, string $name, string $slug, string $parentId)
    {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->slug = $slug;
        $this->parentId = $parentId;
    }
}
