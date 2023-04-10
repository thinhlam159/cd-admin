<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\Pagination;

interface IVatRepository
{
    /**
     * @param Vat $vat
     * @return VatId|null
     */
    public function create(Vat $vat): ?VatId;

    /**
     * @param CustomerId $customerId
     * @return array{Vat[], Pagination}
     */
    public function findAllByCustomerId(CustomerId $customerId): array;

    /**
     * @param VatId $vatId
     * @return Vat|null
     */
    public function findById(VatId $vatId): ?Vat;

    /**
     * @param VatId $vatId
     * @return bool
     */
    public function deleteById(VatId $vatId): bool;
}
