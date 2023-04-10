<?php

namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\DealerId;
use App\Bundle\ProductBundle\Domain\Model\ExportGood;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodCriteria;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodId;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodProduct;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodProductId;
use App\Bundle\ProductBundle\Domain\Model\IExportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGood;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodCriteria;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodId;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProduct;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProductId;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\ExportGood as ModelExportGood;
use App\Models\ExportGoodProduct as ModelExportGoodProduct;

class ExportGoodRepository implements IExportGoodRepository
{
    /**
     * @inheritDoc
     */
    public function create(ExportGood $exportGood): ?ExportGoodId
    {
        $result = ModelExportGood::create([
            'id' => $exportGood->getExportGoodId()->asString(),
            'user_id' => $exportGood->getUserId()->asString(),
            'export_good_date' => $exportGood->getDate()->asString(),
        ]);

        if (!$result) {
            return null;
        }

        return $exportGood->getExportGoodId();
    }

    /**
     * @inheritDoc
     */
    public function createExportGoodProducts(array $exportGoodProducts): bool
    {
        foreach ($exportGoodProducts as $exportGoodProduct) {
            $result = ModelExportGoodProduct::create([
                'id' => $exportGoodProduct->getExportGoodProductId()->asString(),
                'export_good_id' => $exportGoodProduct->getExportGoodId()->asString(),
                'product_id' => $exportGoodProduct->getProductId()->asString(),
                'product_attribute_value_id' => $exportGoodProduct->getProductAttributeValueId()->asString(),
                'count' => $exportGoodProduct->getCount(),
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
            !is_null($entity['dealer_id']) ? new DealerId($entity['dealer_id']) : null,
            new UserId(($entity['user_id'])),
            SettingDate::fromYmdHis($entity['import_good_date']),
            $entity['container_name']
        );
    }

    /**
     * @inheritDoc
     */
    public function findExportGoodProductByExportGoodId(ExportGoodId $exportGoodId): array
    {
        $entities = ModelExportGoodProduct::where('export_good_id', $exportGoodId->asString())->get();

        $exportGoodProducts = [];
        foreach ($entities as $entity) {
            $exportGoodProducts[] = new ExportGoodProduct(
                new ExportGoodProductId($entity['id']),
                new ExportGoodId($entity['export_good_id']),
                new ProductId($entity['product_id']),
                new ProductAttributeValueId($entity['product_attribute_value_id']),
                $entity['count'],
                MeasureUnitType::fromType(MeasureUnitType::ROLL),
            );
        }

        return $exportGoodProducts;
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
    public function findAll(ExportGoodCriteria $exportGoodCriteria): array
    {
        $entities = ModelExportGood::orderBy('export_good_date', 'DESC')->orderBy('created_at', 'DESC')->paginate(PaginationConst::PAGINATE_ROW);

        $exportGoods = [];
        foreach ($entities as $entity) {
            $exportGoods[] = new ExportGood(
                new ExportGoodId($entity['id']),
                new UserId(($entity['user_id'])),
                SettingDate::fromYmdHis($entity['export_good_date']),
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$exportGoods, $pagination];
    }
}
