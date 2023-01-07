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
            'rest_debt' => $debtHistory->getRestDebt(),
            'is_current' => $debtHistory->isCurrent(),
            'update_type' => $debtHistory->getDebtHistoryUpdateType()->getType(),
            'order_id' => !is_null($debtHistory->getOrderId()) ? $debtHistory->getOrderId()->asString() : null,
            'container_order_id' => !is_null($debtHistory->getContainerOrderId()) ? $debtHistory->getContainerOrderId()->asString() : null,
            'vat_id' => !is_null($debtHistory->getVatId()) ? $debtHistory->getVatId()->asString() : null,
            'other_debt_id' => !is_null($debtHistory->getOtherDebtId()) ? $debtHistory->getDebtHistoryId()->asString() : null,
            'payment_id' => !is_null($debtHistory->getPaymentId()) ? $debtHistory->getPaymentId()->asString() : null,
            'update_date' => $debtHistory->getUpdateDate(),
            'number_of_money' => $debtHistory->getNumberOfMoney(),
            'monetary_unit_type' => $debtHistory->getMonetaryUnitType()->getType(),
            'comment' => $debtHistory->getComment(),
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
        ])->first();

        if (!$entity) {
            return null;
        }

        return new DebtHistory(
            new DebtHistoryId($entity->id),
            new CustomerId($entity->customer_id),
            new UserId($entity->user_id),
            $entity->total_debt,
            $entity->total_payment,
            $entity->rest_debt,
            $entity->is_current,
            DebtHistoryUpdateType::fromType($entity->update_type),
            !is_null($entity->order_id) ? new OrderId($entity->order_id) : null,
            !is_null($entity->container_order_id) ? new ContainerOrderId($entity->container_order_id) : null,
            !is_null($entity->vat_id) ? new VatId($entity->vat_id) : null,
            !is_null($entity->payment_id) ? new PaymentId($entity->payment_id) : null,
            !is_null($entity->other_debt_id) ? new OtherDebtId($entity->other_debt_id) : null,
            $entity->number_of_money,
            $entity->update_date,
            MonetaryUnitType::fromType($entity->monetary_unit_type),
            $entity->comment,
            $entity->version
        );
    }

    /**
     * @inheritDoc
     */
    public function findAllCurrentByCustomer(DebtHistoryCriteria $criteria): array
    {
        $keyword = !is_null($criteria->getKeyword()) ? $criteria->getKeyword() : null;
        $order = 'updated_at';
        $sort = 'DESC';
        if (!is_null($criteria->getOrder())) {
            $order = $criteria->getOrder();
            $sort = $criteria->getSort();
        }
        $entities = ModelDebtHistory::where([['is_current', '=', true]])
            ->whereHas('customer', function ($query) use ($keyword) {$query->where('name', 'like', "%$keyword%");})
            ->orderBy($order, $sort)
            ->paginate(PaginationConst::PAGINATE_ROW);

        $debts = [];
        foreach ($entities as $entity) {
            $debts[] = new DebtHistory(
                new DebtHistoryId($entity->id),
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                $entity->total_debt,
                $entity->total_payment,
                $entity->rest_debt,
                $entity->is_current,
                DebtHistoryUpdateType::fromType($entity->update_type),
                !is_null($entity->order_id) ? new OrderId($entity->order_id) : null,
                !is_null($entity->container_order_id) ? new ContainerOrderId($entity->container_order_id) : null,
                !is_null($entity->vat_id) ? new VatId($entity->vat_id) : null,
                !is_null($entity->payment_id) ? new PaymentId($entity->payment_id) : null,
                !is_null($entity->other_debt_id) ? new OtherDebtId($entity->other_debt_id) : null,
                $entity->number_of_money,
                $entity->update_date,
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->comment,
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
    public function findAllHistoryByCustomerId(CustomerDebtHistoryCriteria $criteria): array
    {
        $entities = ModelDebtHistory::where([
            ['customer_id', '=', $criteria->getCustomerId()->asString(),]
        ])->paginate(PaginationConst::PAGINATE_ROW);

        $debts = [];
        foreach ($entities as $entity) {
            $debts[] = new DebtHistory(
                new DebtHistoryId($entity->id),
                new CustomerId($entity->customer_id),
                new UserId($entity->user_id),
                $entity->total_debt,
                $entity->total_payment,
                $entity->rest_debt,
                $entity->is_current,
                DebtHistoryUpdateType::fromType($entity->update_type),
                !is_null($entity->order_id) ? new OrderId($entity->order_id) : null,
                !is_null($entity->container_order_id) ? new ContainerOrderId($entity->container_order_id) : null,
                !is_null($entity->vat_id) ? new VatId($entity->vat_id) : null,
                !is_null($entity->payment_id) ? new PaymentId($entity->payment_id) : null,
                !is_null($entity->other_debt_id) ? new OtherDebtId($entity->other_debt_id) : null,
                $entity->number_of_money,
                $entity->update_date,
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->comment,
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
    public function updateCurrentDebtHistory(DebtHistoryId $debtHistoryId): bool
    {
        $entity = ModelDebtHistory::find($debtHistoryId->asString());
        $result = $entity->update(['is_current' => false]);
        if (!$result) {
            return false;
        }

        return true;
    }
}
