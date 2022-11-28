<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPostCommand
{
    public string $name;
    public string $code;
    public string $description;
    public string $categoryId;

    /**
     * @param string $name
     * @param string $code
     * @param string $description
     * @param string $categoryId
     */
    public function __construct(string $name, string $code, string $description, string $categoryId)
    {
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->categoryId = $categoryId;
    }
}
