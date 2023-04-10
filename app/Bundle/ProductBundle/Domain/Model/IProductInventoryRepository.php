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
     * @param ProductInventoryOrder[] $productInventories
     * @return bool
     */
    public function createMultiProductInventoryByOrder(array $productInventories): bool;

    /**
     * @param ProductInventoryImportGood[] $productInventoryImportGoods
     * @return bool
     */
    public function createMultiProductInventoryByImportGood(array $productInventoryImportGoods): bool;

    /**
     * @param ProductInventoryExportGood[] $productInventoryExportGoods
     * @return bool
     */
    public function createMultiProductInventoryByExportGood(array $productInventoryExportGoods): bool;
}
