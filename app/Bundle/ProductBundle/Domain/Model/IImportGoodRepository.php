<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

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
}
