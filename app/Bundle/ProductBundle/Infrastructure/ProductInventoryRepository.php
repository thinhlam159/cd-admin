<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventory;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\ProductInventory as ModelProductInventory;
use PHPUnit\Framework\Exception;

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
            'is_current' => $productInventory->isCurrent(),
        ]);
        if (!$result) {
            return null;
        }

        return $productInventory->getProductInventoryId();
    }
}
