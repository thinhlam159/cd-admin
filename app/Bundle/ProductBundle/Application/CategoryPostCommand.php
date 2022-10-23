<?php

namespace App\Bundle\ProductBundle\Application;

class CategoryPostCommand
{
    public string $name;
    public string $slug;
    public ?string $parentId;

    /**
     * @param string $name
     * @param string $slug
     * @param string|null $parentId
     */
    public function __construct(string $name, string $slug, ?string $parentId)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->parentId = $parentId;
    }
}
