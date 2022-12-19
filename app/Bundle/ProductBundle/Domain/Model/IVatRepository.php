<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IVatRepository
{
    /**
     * @param Vat $vat
     * @return VatId|null
     */
    public function create(Vat $vat): ?VatId;
}
