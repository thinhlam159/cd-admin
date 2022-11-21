<?php

namespace App\Bundle\ProductBundle\Application;

class CategoryPostResult
{
    public string $categoryId;

    public function __construct(string $categoryId)
    {
        $this->categoryId = $categoryId;
    }
}
