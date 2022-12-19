<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrderId;
use App\Bundle\ProductBundle\Domain\Model\CustomerDebtHistoryCriteria;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryCriteria;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OtherDebtId;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\ProductBundle\Domain\Model\VatId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\DebtHistory as ModelDebtHistory;

class DebtHistoryRepository implements IDebtHistoryRepository
{
    /**
     * @inheritDoc
     */
    public function create(DebtHistory $debtHistory): ?DebtHistoryId
    {
        $result = ModelDebtHistory::create([
            'id' => $debtHistory->getDebtHistoryId()->asString(),
            'customer_id' => $debtHistory->getCustomerId()->asString(),
            'user_id' => $debtHistory->getUserId()->asString(),
            'total_debt' => $debtHistory->getTotalDebt(),
            'total_payment' => $debtHistory->getTotalPayment(),
            'is_current' => $debtHistory->isCurrent(),
            'update_type' => $debtHistory->getDebtHistoryUpdateType()->getType(),
            'order_id' => $debtHistory->getOrderId()->asString(),
            'container_order_id' => $debtHistory->getContainerOrderId()->asString(),
            'vat_id' => $debtHistory->getVatId()->asString(),
            'other_debt_id' => $debtHistory->getOtherDebtId()->asString(),
            'payment_id' => $debtHistory->getPaymentId()->asString(),
            'update_date' => $debtHistory->getUpdateDate(),
            'number_of_money' => $debtHistory->getNumberOfMoney(),
            'monetary_unit_type' => $debtHistory->getMonetaryUnitType()->getType(),
            'version' => $debtHistory->getVersion(),
    	]);
        if (!$result) {
            return null;
        }

        return $debtHistory->getDebtHistoryId();
    }

    /**
     * @inheritDoc
     */
    public function findCurrentDebtByCustomerId(CustomerId $customerId): ?DebtHistory
    {
        $entity = ModelDebtHistory::where([
            ['is_current', '=', true],
            ['customer_id', '=', $customerId->asString()],
        ])->get();

        if (!$entity) {
            return null;
        }

        return new DebtHistory(
            new DebtHistoryId($entity->id),
            new CustomerId($entity->id),
            new UserId($entity->id),
            $entity->total_debt,
            $entity->total_payment,
            $entity->is_current,
            DebtHistoryUpdateType::fromType($entity->debt_history_update_type),
            !is_null($entity->order_id) ? new OrderId($entity->order_id) : null,
            !is_null($entity->container_order_id) ? new ContainerOrderId($entity->container_order_id) : null,
            !is_null($entity->vat_id) ? new VatId($entity->vat_id) : null,
            !is_null($entity->payment_id) ? new PaymentId($entity->payment_id) : null,
            !is_null($entity->other_debt_id) ? new OtherDebtId($entity->other_debt_id) : null,
            $entity->number_of_money,
            $entity->update_date,
            MonetaryUnitType::fromType($entity->monetary_unit_type),
            $entity->version
        );
    }

    /**
     * @inheritDoc
     */
    public function findAllCurrentByCustomer(DebtHistoryCriteria $criteria): array
    {
        $entities = ModelDebtHistory::where([['is_current', '=', true]])->paginate(PaginationConst::PAGINATE_ROW);

        $debts = [];
        foreach ($entities as $entity) {
            $debts[] = new DebtHistory(
                new DebtHistoryId($entity->id),
                new CustomerId($entity->id),
                new UserId($entity->id),
                $entity->total_debt,
                $entity->total_payment,
                $entity->is_current,
                DebtHistoryUpdateType::fromType($entity->debt_history_update_type),
                !is_null($entity->order_id) ? new OrderId($entity->order_id) : null,
                !is_null($entity->container_order_id) ? new ContainerOrderId($entity->container_order_id) : null,
                !is_null($entity->vat_id) ? new VatId($entity->vat_id) : null,
                !is_null($entity->payment_id) ? new PaymentId($entity->payment_id) : null,
                !is_null($entity->other_debt_id) ? new OtherDebtId($entity->other_debt_id) : null,
                $entity->number_of_money,
                $entity->update_date,
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->version
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$debts, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findAllHistoryByCustomer(CustomerDebtHistoryCriteria $criteria): array
    {
        $entities = ModelDebtHistory::where(['customer_id', '=', $criteria->getCustomerId()->asString()])->paginate(PaginationConst::PAGINATE_ROW);

        $debts = [];
        foreach ($entities as $entity) {
            $debts[] = new DebtHistory(
                new DebtHistoryId($entity->id),
                new CustomerId($entity->id),
                new UserId($entity->id),
                $entity->total_debt,
                $entity->total_payment,
                $entity->is_current,
                DebtHistoryUpdateType::fromType($entity->debt_history_update_type),
                !is_null($entity->order_id) ? new OrderId($entity->order_id) : null,
                !is_null($entity->container_order_id) ? new ContainerOrderId($entity->container_order_id) : null,
                !is_null($entity->vat_id) ? new VatId($entity->vat_id) : null,
                !is_null($entity->payment_id) ? new PaymentId($entity->payment_id) : null,
                !is_null($entity->other_debt_id) ? new OtherDebtId($entity->other_debt_id) : null,
                $entity->number_of_money,
                $entity->update_date,
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->version
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$debts, $pagination];
    }
}
