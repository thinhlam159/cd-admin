<?php

namespace App\Bundle\ProductBundle\Application;

class CategoryGetCommand
{
    public string $categoryId;

    public function __construct(string $categoryId)
    {
        $this->categoryId = $categoryId;
    }
}
