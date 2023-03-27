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
use App\Bundle\ProductBundle\Domain\Model\DebtsCustomerExcelCriteria;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OtherDebtId;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalDebtCriteria;
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
            'updated_date' => $debtHistory->getUpdateDate()->asString(),
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
            ['customer_id', '=', $customerId->asString()],
        ])->first();

        if (!$entity) {
            return null;
        }
        $totalDebt = ModelDebtHistory::where([
            ['customer_id', '=', $customerId->asString()],
            ['update_type', '!=', DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::PAYMENT)]
        ])->sum('number_of_money');
        $totalPayment = ModelDebtHistory::where([
            ['customer_id', '=', $customerId->asString()],
            ['update_type', '=', DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::PAYMENT)]
        ])->sum('number_of_money');

        return new DebtHistory(
            new DebtHistoryId($entity->id),
            new CustomerId($entity->customer_id),
            new UserId($entity->user_id),
            (int)$totalDebt,
            (int)$totalPayment,
            (int)$totalDebt - (int)$totalPayment,
            $entity->is_current,
            DebtHistoryUpdateType::fromType($entity->update_type),
            null,
            null,
            null,
            null,
            null,
            $entity->number_of_money,
            SettingDate::fromYmdHis($entity->updated_date),
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
                SettingDate::fromYmdHis($entity->updated_date),
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
        ])->orderBy('updated_date', 'DESC')->paginate(PaginationConst::PAGINATE_ROW);

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
                SettingDate::fromYmdHis($entity->updated_date),
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

    /**
     * @inheritDoc
     */
    public function update(DebtHistory $debtHistory): ?DebtHistoryId
    {
        $entity = ModelDebtHistory::find($debtHistory->getDebtHistoryId()->asString());

        $result = $entity->update([
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
            'update_date' => $debtHistory->getUpdateDate()->asTimeStamps(),
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
    public function initCustomerDebtHistory(DebtHistory $debtHistory): ?DebtHistoryId
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
            'updated_date' => $debtHistory->getUpdateDate()->asString(),
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
    public function findAllHistoryByCustomerId2(DebtsCustomerExcelCriteria $criteria): array
    {
        $entities = ModelDebtHistory::where([
            ['customer_id', '=', $criteria->getCustomerId()->asString(),]
        ])->get();

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
                SettingDate::fromYmdHis($entity->updated_date),
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->comment,
                $entity->version
            );
        }

        return $debts;
    }

    /**
     * @inheritDoc
     */
    public function findAllHistoryByCustomerId3(CustomerId $customerId): array{
        $entities = ModelDebtHistory::where([
            ['customer_id', '=', $customerId->asString(),]
        ])->orderBy('created_at', 'ASC')->get();

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
                SettingDate::fromYmdHis($entity->updated_date),
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->comment,
                $entity->version
            );
        }

        return $debts;
    }

    /**
     * @inheritDoc
     */
    public function findAllByStatistical(StatisticalDebtCriteria $criteria): array
    {
        $conditions = [];
        $conditions[] = ['update_type', '=', DebtHistoryUpdateType::ORDER];
        if (!is_null($criteria->getDate())) {
            $conditions[] = ['updated_date', '=', $criteria->getDate()->asString()];
        }

        $entities = ModelDebtHistory::where($conditions)->get();

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
                SettingDate::fromYmdHis($entity->updated_date),
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->comment,
                $entity->version
            );
        }

        return $debts;
    }

    /**
     * @inheritDoc
     */
    public function findAllByPeriodRevenue(StatisticalDebtCriteria $criteria): array
    {
        $conditions = [];
        $conditions[] = ['update_type', '!=', DebtHistoryUpdateType::PAYMENT];
        if (!is_null($criteria->getStartDate())) {
            $conditions[] = ['updated_date', '>=', $criteria->getStartDate()->asString()];
        }
        if (!is_null($criteria->getEndDate())) {
            $conditions[] = ['updated_date', '<=', $criteria->getEndDate()->asString()];
        }

        $entities = ModelDebtHistory::where($conditions)->get();

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
                SettingDate::fromYmdHis($entity->updated_date),
                MonetaryUnitType::fromType($entity->monetary_unit_type),
                $entity->comment,
                $entity->version
            );
        }

        return $debts;
    }

    /**
     * @inheritDoc
     */
    public function findByPaymentId(PaymentId $paymentId): ?DebtHistory
    {
        $entity = ModelDebtHistory::where([['payment_id', '=', $paymentId->asString()]])->first();
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
            null,
            null,
            null,
            new PaymentId($entity->payment_id),
            null,
            $entity->number_of_money,
            SettingDate::fromYmdHis($entity->updated_date),
            MonetaryUnitType::fromType($entity->monetary_unit_type),
            $entity->comment,
            $entity->version
        );
    }

    /**
     * @inheritDoc
     */
    public function findByOrderId(OrderId $orderId): ?DebtHistory
    {
        $entity = ModelDebtHistory::where([['order_id', '=', $orderId->asString()]])->first();
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
            new OrderId($entity->order_id),
            null,
            null,
            null,
            null,
            $entity->number_of_money,
            SettingDate::fromYmdHis($entity->updated_date),
            MonetaryUnitType::fromType($entity->monetary_unit_type),
            $entity->comment,
            $entity->version
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteById(DebtHistoryId $debtHistoryId): bool
    {
        $result = ModelDebtHistory::find($debtHistoryId->asString())->delete();
        if (!$result) return false;

        return true;
    }
}
