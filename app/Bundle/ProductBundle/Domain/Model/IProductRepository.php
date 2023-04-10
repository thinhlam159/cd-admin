<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductRepository
{
    /**
     * @param Product $category
     * @return ProductId
     */
    public function create(Product $category): ProductId;

    /**
     * @param ProductCriteria $criteria
     * @return array{Product[], Pagination}
     */
    public function findAll(ProductCriteria $criteria): array;

    /**
     * @param ProductId $productId
     * @return Product|null
     */
    public function findById(ProductId $productId): ?Product;

    /**
     * @param Product $product
     * @return ProductId
     */
    public function update(Product $product): ProductId;

    /**
     * @param CategoryId $categoryId
     * @return Product[]
     */
    public function findByCategoryId(CategoryId $categoryId): array;
}
