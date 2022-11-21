<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IMeasureUnitRepository
{
    /**
     * @return MeasureUnit[]
     */
    public function findAll(): array;

    /**
     * @param MeasureUnitId $measureUnitId
     * @return MeasureUnit|null
     */
    public function findById(MeasureUnitId $measureUnitId): ?MeasureUnit;
}
