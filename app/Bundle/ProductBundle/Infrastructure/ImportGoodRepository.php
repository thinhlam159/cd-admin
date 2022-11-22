<?php

namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IImportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGood;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodId;
use App\Models\ImportGood as ModelImportGood;
use App\Models\ImportGoodProduct as ModelImportGoodProduct;

class ImportGoodRepository implements IImportGoodRepository
{
    /**
     * @inheritDoc
     */
    public function create(ImportGood $importGood): ?ImportGoodId
    {
        $result = ModelImportGood::create([
            'id' => $importGood->getImportGoodId()->asString(),
            'dealer_id' => $importGood->getDealerId()->asString(),
            'user_id' => $importGood->getImportGoodId()->asString(),
        ]);

        if (!$result) {
            return null;
        }

        return $importGood->getImportGoodId();
    }

    /**
     * @inheritDoc
     */
    public function createImportGoodProducts(array $importGoodProducts): bool
    {
        foreach ($importGoodProducts as $importGoodProduct) {
            $result = ModelImportGoodProduct::create([
                'id' => $importGoodProduct->getGoodProductId()->asString(),
                'product_id' => $importGoodProduct->getGoodProductId()->asString(),
                'product_attribute_value_id' => $importGoodProduct->getGoodProductId()->asString(),
                'price' => $importGoodProduct->getPrice(),
                'monetary_unit_type' => $importGoodProduct->getMonetaryUnitType()->getType(),
                'count' => $importGoodProduct->getCount(),
                'measure_unit_type' => $importGoodProduct->getMeasureUnitType()->getType()
            ]);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
