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
     * @return array{Product[], Pagination}
     */
    public function findAll(): array;
}
