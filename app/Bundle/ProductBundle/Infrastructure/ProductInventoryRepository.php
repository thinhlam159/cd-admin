<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventory;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Models\ProductInventory as ModelProductInventory;

final class ProductInventoryRepository implements IProductInventoryRepository
{
    /**
     * @inheritDoc
     */
    public function findByProductAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductInventory
    {
        $entity = ModelProductInventory::find(1)->where([
            'product_attribute_value_id'=> $productAttributeValueId->asString(),
            'is_current' => true,
        ])->latest()->first();
        if (!$entity) {
            return null;
        }

        return new ProductInventory(
            new ProductInventoryId($entity['id']),
            $productAttributeValueId,
            $entity['count'],
            MeasureUnitType::fromType($entity['measure_unit_type']),
            $entity['is_current']
        );
    }

    /**
     * @inheritDoc
     */
    public function create(ProductInventory $productInventory): ?ProductInventoryId
    {
        $result = ModelProductInventory::create([
            'id'=> $productInventory->getProductInventoryId()->asString(),
            'product_attribute_value_id'=> $productInventory->getProductAttributeValueId()->asString(),
            'count' => $productInventory->getCount(),
            'measure_unit_type' => $productInventory->getMeasureUnitType()->getType(),
            'is_current' => $productInventory->isCurrent(),
        ]);
        if (!$result) {
            return null;
        }

        return $productInventory->getProductInventoryId();
    }

    /**
     * @inheritDoc
     */
    public function updateProductInventories(array $productInventories): bool
    {
        foreach ($productInventories as $inventory) {
            $entity = ModelProductInventory::find($inventory->getProductInventoryId()->asString());
            $result = $entity->update([
                'is_current' => false,
            ]);

            if (!$result) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function createMultiProductInventoryByOrder(array $productInventories): bool
    {
        foreach ($productInventories as $inventory) {
            $result = ModelProductInventory::create([
                'id' => $inventory->getProductInventoryId()->asString(),
                'product_attribute_value_id' => $inventory->getProductAttributeValueId()->asString(),
                'count' => $inventory->getCount(),
                'update_type' => $inventory->getProductInventoryUpdateType()->getType(),
                'order_id' => $inventory->getOrderId()->asString(),
                'number_of_update' => $inventory->getNumberOfUpdate(),
                'measure_unit_type' => $inventory->getMeasureUnitType()->getType(),
                'is_current' => true,
            ]);

            if (!$result) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function createMultiProductInventoryByImportGood(array $productInventoryImportGoods): bool
    {
        foreach ($productInventoryImportGoods as $inventory) {
            $result = ModelProductInventory::create([
                'id' => $inventory->getProductInventoryId()->asString(),
                'product_attribute_value_id' => $inventory->getProductAttributeValueId()->asString(),
                'count' => $inventory->getCount(),
                'update_type' => $inventory->getProductInventoryUpdateType()->getType(),
                'import_good_product_id' => $inventory->getImportGoodProductId()->asString(),
                'number_of_update' => $inventory->getNumberOfUpdate(),
                'measure_unit_type' => $inventory->getMeasureUnitType()->getType(),
                'is_current' => true,
            ]);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
