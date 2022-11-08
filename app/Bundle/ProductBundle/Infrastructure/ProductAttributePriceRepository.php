<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
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
            'monetary_unit' => $productAttributePrice->getMonetaryUnitType()->getValue(),
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
    public function findByAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductAttributePrice
    {
        $result = ModelProductAttributePrice::where('product_attribute_value_id', $productAttributeValueId->asString())->latest()->first();
        if (!$result) {
            return null;
        }

        return new ProductAttributePrice(
            new ProductAttributePriceId($result['id']),
            new ProductAttributeValueId($result['product_attribute_value_id']),
            $result['price'],
            MonetaryUnitType::fromValue($result['monetary_unit']),
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
            'monetary_unit' => $productAttributePrice->getMonetaryUnitType()->getValue(),
            'is_current' => $productAttributePrice->isCurrent(),
        ]);
        if (!$result) {
            throw new \Exception();
        }

        return $productAttributePrice->getProductAttributePriceId();
    }
}
