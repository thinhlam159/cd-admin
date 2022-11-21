<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface ICategoryRepository
{
    /**
     * @param Category $category
     * @return CategoryId
     */
    public function create(Category $category): CategoryId;

    /**
     * @noparam
     * @return array{Category[], Pagination}
     */
    public function findAll(): array;

    /**
     * @param CategoryId $categoryId categoryId
     * @return Category|null
     */
    public function findById(CategoryId $categoryId): ?Category;

    /**
     * @param Category $category
     * @return CategoryId
     */
    public function update(Category $category): CategoryId;
}
