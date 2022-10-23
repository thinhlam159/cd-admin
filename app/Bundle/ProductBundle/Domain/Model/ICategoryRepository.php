<?php
namespace App\Bundle\ProductBundle\Domain\Model;

interface ICategoryRepository
{
    /**
     * @param Category $category
     * @return CategoryId
     */
    public function create(Category $category): CategoryId;

    /**
     * @noparam
     * @return Category[]
     */
    public function findAll(): array;
}
