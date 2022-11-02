<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitId;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValue;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\ProductAttributeValue as ModelProductAttributeValue;
use PHPUnit\Framework\Exception;

class ProductAttributeValueRepository implements IProductAttributeValueRepository
{
    /**
     * @inheritDoc
     */
    public function create(ProductAttributeValue $productAttributeValue): ProductAttributeValueId
    {
        $result = ModelProductAttributeValue::create([
            'id' => $productAttributeValue->getProductAttributeValueId()->asString(),
            'product_id' => $productAttributeValue->getProductId()->asString(),
            'product_attribute_id' => $productAttributeValue->getProductAttributeId()->asString(),
            'measure_unit_id' => $productAttributeValue->getMeasureUnitId()->asString(),
            'value' => $productAttributeValue->getValue(),
            'name_by_attribute' => $productAttributeValue->getNameByAttribute(),
        ]);
        if (!$result) {
            throw new \Exception();
        }

        return new ProductAttributeValueId($result->id);
    }

    /**
     * @inheritDoc
     */
    public function findById(ProductAttributeValueId $productAttributeValueId): ?ProductAttributeValue
    {
        $entity = ModelProductAttributeValue::find($productAttributeValueId->asString());

        if (!$entity) {
            return null;
        }

        return new ProductAttributeValue(
            new ProductAttributeValueId($entity['id']),
            new ProductId($entity['id']),
            new ProductAttributeId($entity['id']),
            new MeasureUnitId($entity['id']),
            $entity['value'],
            $entity['name_by_attribute'],
        );
    }

    /**
     * @inheritDoc
     */
    public function findByProductId(ProductId $productId): array
    {
        $entities = ModelProductAttributeValue::find('product_id', $productId->asString());

        $productAttributeValues = [];
        foreach ($entities as $entity) {
            $productAttributeValue = new ProductAttributeValue(
                new ProductAttributeValueId($entity['id']),
                new ProductId($entity['id']),
                new ProductAttributeId($entity['id']),
                new MeasureUnitId($entity['id']),
                $entity['value'],
                $entity['name_by_attribute'],
            );
            $productAttributeValue->setMeasureUnitName($entity->measureUnit->name);
            $productAttributeValue->setProductAttributeName($entity->productAttribute->name);

            $productAttributeValues[] = $productAttributeValue;
        }

        return $productAttributeValues;
    }

    public function update(ProductAttributeValue $productAttributeValue): ProductAttributeValueId
    {
        $entity = ModelProductAttributeValue::find($productAttributeValue->getProductAttributeValueId()->asString());

        $result = $entity->update([
            'id' => $productAttributeValue->getProductAttributeValueId()->asString(),
            'product_id' => $productAttributeValue->getProductId()->asString(),
            'product_attribute_id' => $productAttributeValue->getProductAttributeId()->asString(),
            'measure_unit_id' => $productAttributeValue->getMeasureUnitId()->asString(),
            'value' => $productAttributeValue->getValue(),
            'name_by_attribute' => $productAttributeValue->getNameByAttribute(),
        ]);
        if (!$result) {
            if
        }
    }

}
