<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IExportGoodRepository
{
    /**
     * @param ExportGood $exportGood
     * @return ExportGoodId|null
     */
    public function create(ExportGood $exportGood): ?ExportGoodId;

    /**
     * @param ExportGoodProduct[] $exportGoodProducts
     * @return bool
     */
    public function createExportGoodProducts(array $exportGoodProducts): bool;

    /**
     * @param ImportGoodId $importGoodId
     * @return ImportGood|null
     */
    public function findById(ImportGoodId $importGoodId): ?ImportGood;

    /**
     * @param ExportGoodId $exportGoodId
     * @return ExportGoodProduct[]
     */
    public function findExportGoodProductByExportGoodId(ExportGoodId $exportGoodId): array;

    /**
     * @param ImportGoodId $importGoodId
     * @return bool
     */
    public function restoreImportGood(ImportGoodId $importGoodId): bool;

    /**
     * @param ExportGoodCriteria $exportGoodCriteria
     * @return array{ExportGood[], Pagination}
     */
    public function findAll(ExportGoodCriteria $exportGoodCriteria): array;
}
