<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Models\ProductAttribute as ModelProductAttribute;

class ProductAttributeRepository implements IProductAttributeRepository
{
    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelProductAttribute::all();

        $productAttribute = [];
        foreach ($entities as $entity) {
            $productAttribute[] = new ProductAttribute(
                new ProductAttributeId($entity['id']),
                $entity['name']
            );
        }

        return $productAttribute;
    }

    /**
     * @inheritDoc
     */
    public function findById(ProductAttributeId $productAttributeId): ?ProductAttribute
    {
        $entity = ModelProductAttribute::where('id', $productAttributeId->asString())->first();

        return new ProductAttribute(
            $productAttributeId,
            $entity['name']
        );
    }
}
