<?php
namespace App\Bundle\ProductBundle\Domain\Model;

interface IImportGoodRepository
{
    /**
     * @param ImportGood $importGood
     * @return ImportGoodId|null
     */
    public function create(ImportGood $importGood): ?ImportGoodId;

    /**
     * @param ImportGoodProduct[] $importGoodProducts
     * @return bool
     */
    public function createImportGoodProducts(array $importGoodProducts): bool;

    /**
     * @param ImportGoodId $importGoodId
     * @return ImportGood|null
     */
    public function findById(ImportGoodId $importGoodId): ?ImportGood;

    /**
     * @param ImportGoodId $importGoodId
     * @return ImportGoodProduct[]
     */
    public function findImportGoodProductByImportGoodId(ImportGoodId $importGoodId): array;

    /**
     * @param ImportGoodId $importGoodId
     * @return bool
     */
    public function restoreImportGood(ImportGoodId $importGoodId): bool;
}
