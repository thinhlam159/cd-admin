<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\IVatRepository;
use App\Bundle\ProductBundle\Domain\Model\Vat;
use App\Bundle\ProductBundle\Domain\Model\VatId;
use App\Models\Vat as ModelVat;

class VatRepository implements IVatRepository
{
    /**
     * @inheritDoc
     */
    public function create(Vat $vat): ?VatId
    {
        $result = ModelVat::create([
            'id' => $vat->getVatId()->asString(),
            'cost' => $vat->getCost(),
            'monetary_unit_type' => $vat->getMonetaryUnitType()->getType(),
            'comment' => $vat->getComment(),
            'customer_id' => $vat->getCustomerId()->asString(),
            'user_id' => $vat->getUserId()->asString(),
            'arising_date' => $vat->getArisingDate()->asString(),
            'payment_status' => $vat->getPaymentStatus()->getStatus(),
    	]);
        if (!$result) {
            return null;
        }

        return $vat->getVatId();
    }
}
