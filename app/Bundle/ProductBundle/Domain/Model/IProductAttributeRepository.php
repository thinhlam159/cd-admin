<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributeRepository
{
    /**
     * @return ProductAttribute[]
     */
    public function findAll(): array;

    /**
     * @param ProductAttributeId $productAttributeId
     * @return ProductAttribute|null
     */
    public function findById(ProductAttributeId $productAttributeId): ?ProductAttribute;
}
