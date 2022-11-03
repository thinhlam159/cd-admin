<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributeRepository
{
    /**
     * @return ProductAttribute[]
     */
    public function findAll(): array;
}
