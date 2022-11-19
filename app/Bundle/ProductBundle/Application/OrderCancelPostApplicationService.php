<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\OrderDeliveryStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderCancelPostApplicationService
{
    /**
     * @var IOrderRepository
     */
    private IOrderRepository $orderRepository;

    /**
     * @param IOrderRepository $orderRepository
     */
    public function __construct(IOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param OrderCancelPostCommand $command
     * @return OrderCancelPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderCancelPostCommand $command): OrderCancelPostResult
    {
        $orderId = new OrderId($command->orderId);
        $order = $this->orderRepository->findById($orderId);
        if (!$order) {
            throw new InvalidArgumentException(MessageConst::NOT_FOUND['message']);
        }
        $order->updateCancelStatus(OrderDeliveryStatus::fromValue($command->deliveryStatus));

        DB::beginTransaction();
        try {
            $result = $this->orderRepository->updateDeliveryStatus($order);
            if (!$result) {
                throw new InvalidArgumentException('update delivery status failed!');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new OrderCancelPostResult($orderId->__toString());
    }
}
