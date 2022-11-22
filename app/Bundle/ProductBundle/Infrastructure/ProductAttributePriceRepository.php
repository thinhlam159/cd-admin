<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\NoticePriceType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Models\ProductAttributePrice as ModelProductAttributePrice;

final class ProductAttributePriceRepository implements IProductAttributePriceRepository
{
    /**
     * @inheritDoc
     */
    public function create(ProductAttributePrice $productAttributePrice): ProductAttributePriceId
    {
        $result = ModelProductAttributePrice::create([
            'id' => $productAttributePrice->getProductAttributePriceId()->asString(),
            'product_attribute_value_id' => $productAttributePrice->getProductAttributeValueId()->asString(),
            'price' => $productAttributePrice->getPrice(),
            'monetary_unit_type' => $productAttributePrice->getMonetaryUnitType()->getType(),
            'notice_price_type' => $productAttributePrice->getNoticePriceType()->getType(),
            'is_current' => $productAttributePrice->isCurrent(),
        ]);

        if(!$result) {
            throw new \Exception();
        }

        return $productAttributePrice->getProductAttributePriceId();
    }

    /**
     * @inheritDoc
     */
    public function findByProductAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductAttributePrice
    {
        $result = ModelProductAttributePrice::where([
            'product_attribute_value_id' => $productAttributeValueId->asString(),
            'is_current' => true
        ])->latest()->first();
        if (!$result) {
            return null;
        }

        return new ProductAttributePrice(
            new ProductAttributePriceId($result['id']),
            new ProductAttributeValueId($result['product_attribute_value_id']),
            $result['price'],
            MonetaryUnitType::fromType($result['monetary_unit_type']),
            NoticePriceType::fromType($result['notice_price_type']),
            $result['is_current']
        );
    }

    /**
     * @inheritDoc
     */
    public function update(ProductAttributePrice $productAttributePrice): ProductAttributePriceId
    {
        $entity = ModelProductAttributePrice::find($productAttributePrice->getProductAttributePriceId());
        $result = $entity->update([
            'product_attribute_value_id' => $productAttributePrice->getProductAttributeValueId()->asString(),
            'price' => $productAttributePrice->getPrice(),
            'monetary_unit_type' => $productAttributePrice->getMonetaryUnitType()->getType(),
            'notice_price_type' => $productAttributePrice->getNoticePriceType()->getType(),
            'is_current' => $productAttributePrice->isCurrent(),
        ]);
        if (!$result) {
            throw new \Exception();
        }

        return $productAttributePrice->getProductAttributePriceId();
    }

    /**
     * @inheritDoc
     */
    public function updateMany(array $productAttributePrices): array
    {
        $productAttributePriceIds = [];
        foreach ($productAttributePrices as $productAttributePrice) {
            $entity = ModelProductAttributePrice::find($productAttributePrice->getProductAttributePriceId());
            $result = $entity->update([
                'product_attribute_value_id' => $productAttributePrice->getProductAttributeValueId()->asString(),
                'price' => $productAttributePrice->getPrice(),
                'monetary_unit_type' => $productAttributePrice->getMonetaryUnitType()->getType(),
                'notice_price_type' => $productAttributePrice->getNoticePriceType()->getType(),
                'is_current' => $productAttributePrice->isCurrent(),
            ]);
            if (!$result) {
                throw new \Exception();
            }

            $productAttributePriceIds[] = $productAttributePrice->getProductAttributePriceId();
        }

        return $productAttributePriceIds;
    }

    /**
     * @inheritDoc
     */
    public function createMany(array $productAttributePrices): array
    {
        $productAttributePriceIds = [];
        foreach ($productAttributePrices as $productAttributePrice) {
            $result = ModelProductAttributePrice::create([
                'id' => $productAttributePrice->getProductAttributePriceId()->asString(),
                'product_attribute_value_id' => $productAttributePrice->getProductAttributeValueId()->asString(),
                'price' => $productAttributePrice->getPrice(),
                'monetary_unit_type' => $productAttributePrice->getMonetaryUnitType()->getType(),
                'notice_price_type' => $productAttributePrice->getNoticePriceType()->getType(),
                'is_current' => $productAttributePrice->isCurrent(),
            ]);
            if (!$result) {
                throw new \Exception();
            }

            $productAttributePriceIds[] = $productAttributePrice->getProductAttributePriceId();
        }

        return $productAttributePriceIds;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelProductAttributePrice::where('is_current', true)->get();
        if (!$entities) {
            return [];
        }

        $productAttributePrices = [];
        foreach ($entities as $entity) {
            $productAttributePrices[] = new ProductAttributePrice(
                new ProductAttributePriceId($entity['id']),
                new ProductAttributeValueId($entity['product_attribute_value_id']),
                $entity['price'],
                MonetaryUnitType::fromType((int)$entity['monetary_unit_type']),
                NoticePriceType::fromType((int)$entity['notice_price_type']),
                $entity['is_current']
            );
        }

        return $productAttributePrices;
    }

}
