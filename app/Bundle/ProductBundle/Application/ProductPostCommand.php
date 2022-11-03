<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPostCommand
{
    public string $name;
    public string $code;
    public string $description;
    public string $categoryId;
    public string $path;
    public bool $isAvatar;

    /**
     * @param string $name
     * @param string $code
     * @param string $description
     * @param string $categoryId
     * @param string $path
     * @param bool $isAvatar
     */
    public function __construct(string $name, string $code, string $description, string $categoryId, string $path, bool $isAvatar)
    {
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->path = $path;
        $this->isAvatar = $isAvatar;
    }
}
