<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OrderStatus;
use App\Bundle\ProductBundle\Domain\Model\UserId as ProductBundleUserId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderStatusPutApplicationService
{
    /**
     * @var IOrderRepository
     */
    private IOrderRepository $orderRepository;

    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param IOrderRepository $orderRepository
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        IDebtHistoryRepository $debtHistoryRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->debtHistoryRepository = $debtHistoryRepository;
    }

    /**
     * @param OrderStatusPutCommand $command
     * @return OrderPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderStatusPutCommand $command): OrderPostResult
    {
        $orderId = new OrderId($command->orderId);
        $order = $this->orderRepository->findById($orderId);
        if (!$order) {
            throw new InvalidArgumentException(MessageConst::NOT_FOUND['message']);
        }
        if ($order->getOrderStatus()->getStatus() !== OrderStatus::IN_PROGRESS) {
            throw new InvalidArgumentException(MessageConst::INVALID_ORDER_STATUS['message']);
        }
        $customerId = $order->getCustomerId();
        $userId = new UserId($command->userId);
        $order->setUserId($userId);
        $order->updateResolvedStatus();

        $orderProducts = $this->orderRepository->findOrderProductsByOrderId($orderId);
        $totalOrderCost = 0;
        foreach ($orderProducts as $orderProduct) {
            $totalOrderCost += $orderProduct->getOrderProductCost();
        }

        $currentDebt = $this->debtHistoryRepository->findCurrentDebtByCustomerId($customerId);
        $debtHistoryId = DebtHistoryId::newId();
        $newDebtHistory = new DebtHistory(
            $debtHistoryId,
            $customerId,
            new ProductBundleUserId($userId->asString()),
            !is_null($currentDebt) ? $currentDebt->getTotalDebt() + $totalOrderCost : $totalOrderCost,
            !is_null($currentDebt) ? $currentDebt->getTotalPayment() : 0,
            !is_null($currentDebt) ? $currentDebt->getRestDebt() + $totalOrderCost : $totalOrderCost,
            true,
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::ORDER),
            $orderId,
            null,
            null,
            null,
            null,
            $totalOrderCost,
            $order->getOrderDate(),
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            null,
            !is_null($currentDebt) ? $currentDebt->getVersion() + 1 : 1
        );

        DB::beginTransaction();
        try {
            $orderId = $this->orderRepository->create($order);
            if (!$orderId) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $result = $this->orderRepository->createOrderProducts($orderProducts);
            if (!$result) {
                throw new InvalidArgumentException('customer not exist!');
            }
            if ($currentDebt) {
                $this->debtHistoryRepository->updateCurrentDebtHistory($currentDebt->getDebtHistoryId());
            }
            $this->debtHistoryRepository->create($newDebtHistory);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new OrderPostResult($orderId->__toString());
    }
}
