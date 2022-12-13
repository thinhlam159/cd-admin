<?php

namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\DealerId;
use App\Bundle\ProductBundle\Domain\Model\IImportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGood;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodCriteria;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodId;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProduct;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProductId;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
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
            'dealer_id' => !is_null($importGood->getDealerId()) ? $importGood->getDealerId()->asString() : null,
            'user_id' => $importGood->getUserId()->asString(),
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
                'id' => $importGoodProduct->getImportGoodProductId()->asString(),
                'import_good_id' => $importGoodProduct->getImportGoodId()->asString(),
                'product_id' => $importGoodProduct->getProductId()->asString(),
                'product_attribute_value_id' => $importGoodProduct->getProductAttributeValueId()->asString(),
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

    /**
     * @inheritDoc
     */
    public function findById(ImportGoodId $importGoodId): ?ImportGood
    {
        $entity = ModelImportGood::find($importGoodId->asString());
        if (!$entity) {
            return null;
        }

        return new ImportGood(
            $importGoodId,
            new DealerId($entity['dealer_id']),
            new UserId(($entity['user_id']))
        );
    }

    /**
     * @inheritDoc
     */
    public function findImportGoodProductByImportGoodId(ImportGoodId $importGoodId): array
    {
        $entities = ModelImportGoodProduct::where('import_good_id', $importGoodId->asString())->get();

        $importGoodProducts = [];
        foreach ($entities as $entity) {
            $importGoodProducts[] = new ImportGoodProduct(
                new ImportGoodProductId($entity['id']),
                new ImportGoodId($entity['import_good_id']),
                new ProductId($entity['product_id']),
                new ProductAttributeValueId($entity['product_attribute_value_id']),
                $entity['price'],
                MonetaryUnitType::fromType($entity['monetary_unit_type']),
                $entity['count'],
                MeasureUnitType::fromType($entity['measure_unit_type'])
            );
        }

        return $importGoodProducts;
    }

    /**
     * @inheritDoc
     */
    public function restoreImportGood(ImportGoodId $importGoodId): bool
    {
        $entity = ModelImportGood::find($importGoodId->asString());

        $result = $entity->update(['is_restore' => true]);
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function findAll(ImportGoodCriteria $importGoodCriteria): array
    {
        $entities = ModelImportGood::paginate(PaginationConst::PAGINATE_ROW);

        $importGoods = [];
        foreach ($entities as $entity) {
            $importGoods[] = new ImportGood(
                new ImportGoodId($entity['id']),
                !is_null($entity['dealer_id']) ? new DealerId($entity['dealer_id']) : null,
                new UserId(($entity['user_id']))
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$importGoods, $pagination];
    }
}
