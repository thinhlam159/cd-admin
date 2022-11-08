<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductInventoryRepository
{
    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @return ProductInventory|null
     */
    public function findByProductId(ProductAttributeValueId $productAttributeValueId): ?ProductInventory;

    /**
     * @param ProductInventory $productInventory
     * @return ProductInventoryId|null
     */
    public function create(ProductInventory $productInventory): ?ProductInventoryId;
}
