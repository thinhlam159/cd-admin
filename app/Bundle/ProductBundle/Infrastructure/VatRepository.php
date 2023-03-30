<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\Pagination;
use App\Bundle\ProductBundle\Domain\Model\IVatRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId;
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

    /**
     * @inheritDoc
     */
    public function findAllByCustomerId(CustomerId $customerId): array
    {
        $entities = ModelVat::where([['customer_id', '=', $customerId->asString()],])->orderBy('created_at', 'DESC')->paginate();

        $payments  = [];
        foreach ($entities as $entity) {
            $payments[] = new Vat(
                new VatId($entity->id),
                $entity->cost,
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                $entity->comment,
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                OrderPaymentStatus::fromStatus($entity->payment_status),
                SettingDate::fromYmdHis($entity->arising_date),
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$payments, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findById(VatId $vatId): ?Vat
    {
        $entity = ModelVat::find($vatId->asString());
        if(!$entity) return null;

        return new Vat(
            new VatId($entity->id),
            $entity->cost,
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            $entity->comment,
            new CustomerId($entity->customer_id),
            new UserId($entity->user_id),
            OrderPaymentStatus::fromStatus($entity->payment_status),
            SettingDate::fromYmdHis($entity->arising_date),
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteById(VatId $vatId): bool
    {
        $result = ModelVat::find($vatId->asString())->delete();
        if (!$result) return false;

        return true;
    }
}
