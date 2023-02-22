<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValue;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueCriteria;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Models\ProductAttributeValue as ModelProductAttributeValue;

final class ProductAttributeValueRepository implements IProductAttributeValueRepository
{
    /**
     * @inheritDoc
     */
    public function create(ProductAttributeValue $productAttributeValue): ?ProductAttributeValueId
    {
        $result = ModelProductAttributeValue::create([
            'id' => $productAttributeValue->getProductAttributeValueId()->asString(),
            'product_id' => $productAttributeValue->getProductId()->asString(),
            'product_attribute_id' => $productAttributeValue->getProductAttributeId()->asString(),
            'value' => $productAttributeValue->getValue(),
            'code' => $productAttributeValue->getCode(),
            'is_original' => $productAttributeValue->isOriginal(),
        ]);
        if (!$result) {
            return null;
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
            new ProductId($entity['product_id']),
            new ProductAttributeId($entity['product_attribute_id']),
            $entity['value'],
            $entity['code'],
            null,
            null,
        );
    }

    /**
     * @inheritDoc
     */
    public function findByProductId(ProductId $productId): array
    {
        $conditions = [
            ['product_id', '=', $productId->asString(),],
        ];
        $entities = ModelProductAttributeValue::where($conditions)->get();

        $productAttributeValues = [];
        foreach ($entities as $entity) {
            $productAttributeValue = new ProductAttributeValue(
                new ProductAttributeValueId($entity['id']),
                new ProductId($entity['product_id']),
                new ProductAttributeId($entity['product_attribute_id']),
                $entity['value'],
                $entity['code'],
                null,
                null,
                $entity['is_original'],
            );

            $productAttributeValues[] = $productAttributeValue;
        }

        return $productAttributeValues;
    }

    /**
     * @inheritDoc
     */
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
            throw new \Exception();
        }

        return $productAttributeValue->getProductAttributeValueId();
    }

    /**
     * @inheritDoc
     */
    public function findAll(ProductAttributeValueCriteria $productAttributeValueCriteria): array
    {
        $entities = ModelProductAttributeValue::all();
        if ($entities->isEmpty()) {
            return [];
        }

        $productAttributeValues = [];
        foreach ($entities as $entity) {
            $productAttributePrices = $entity->productAttributePrices();

            $productAttributePriceId = $productAttributePrices->where('is_current', '=', 1)->first()->id;
            $productAttributeValues[] = new ProductAttributeValue(
                new ProductAttributeValueId($entity['id']),
                new ProductId($entity['product_id']),
                new ProductAttributeId($entity['product_attribute_id']),
                $entity['value'],
                $entity['code'],
                null,
                null
            );
        }

        return $productAttributeValues;
    }
}
