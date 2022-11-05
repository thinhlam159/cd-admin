<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\FeatureImagePath;
use App\Bundle\ProductBundle\Domain\Model\FeatureImagePathId;
use App\Bundle\ProductBundle\Domain\Model\IFeatureImagePathRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Models\FeatureImagePath as ModelFeatureImagePath;

class FeatureImagePathRepository implements IFeatureImagePathRepository
{
    /**
     * @inheritDoc
     */
    public function create(FeatureImagePath $featureImagePath): FeatureImagePathId
    {
        $productAttributeValueId = !is_null($featureImagePath->getProductAttributeValueId()) ? $featureImagePath->getProductAttributeValueId()->asString() : null;
        $result = ModelFeatureImagePath::create([
            'id' => $featureImagePath->getFeatureImagePathId()->asString(),
            'product_id' => $featureImagePath->getProductId()->asString(),
            'product_attribute_value_id' => $productAttributeValueId,
            'path' => $featureImagePath->getPath(),
            'is_avatar' => $featureImagePath->isAvatar()
        ]);

        if (!$result) {
            throw new \Exception();
        }

        return $featureImagePath->getFeatureImagePathId();
    }

    public function findByProductId(ProductId $productId): ?FeatureImagePath
    {
        $entity = ModelFeatureImagePath::where([
            'product_id' => $productId->asString(),
            'product_attribute_value_id' => null,
            'is_avatar' => true
        ])->first();

        if (!$entity) {
            return null;
        }

        return new FeatureImagePath(
            new FeatureImagePathId($entity['id']),
            $productId,
            null,
            true,
            $entity['path']
        );
    }
}
