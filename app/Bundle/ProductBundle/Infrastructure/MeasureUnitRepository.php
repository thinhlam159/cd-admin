<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IMeasureUnitRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnit;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Models\MeasureUnit as ModelMeasureUnit;

class MeasureUnitRepository implements IMeasureUnitRepository
{
    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelMeasureUnit::all();

        $measureUnit = [];
        foreach ($entities as $entity) {
            $measureUnit[] = new MeasureUnit(
                new MeasureUnitId($entity['id']),
                $entity['name']
            );
        }

        return $measureUnit;
    }

    /**
     * @inheritDoc
     */
    public function findById(MeasureUnitId $measureUnitId): ?MeasureUnit
    {
        $entity = ModelMeasureUnit::where('id',$measureUnitId->asString())->first();

        return new MeasureUnit(
            $measureUnitId,
            $entity['name']
        );
    }
}
