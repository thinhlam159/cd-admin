<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductInventoryRepository
{
    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @return ProductInventory|null
     */
    public function findByProductAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductInventory;

    /**
     * @param ProductInventory $productInventory
     * @return ProductInventoryId|null
     */
    public function create(ProductInventory $productInventory): ?ProductInventoryId;

    /**
     * @param ProductInventory[] $productInventories
     * @return bool
     */
    public function updateProductInventories(array $productInventories): bool;

    /**
     * @param ProductInventory[] $productInventories
     * @return bool
     */
    public function createMultiProductInventory(array $productInventories): bool;
}
