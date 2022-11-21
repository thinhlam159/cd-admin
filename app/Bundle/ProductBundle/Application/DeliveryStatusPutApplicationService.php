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

class DeliveryStatusPutApplicationService
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
     * @param DeliveryStatusPutCommand $command
     * @return DeliveryStatusPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(DeliveryStatusPutCommand $command): DeliveryStatusPutResult
    {
        $orderId = new OrderId($command->orderId);
        $order = $this->orderRepository->findById($orderId);
        if (!$order) {
            throw new InvalidArgumentException(MessageConst::NOT_FOUND['message']);
        }
        $order->updateDeliveryStatus(OrderDeliveryStatus::fromValue($command->deliveryStatus));

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

        return new DeliveryStatusPutResult($orderId->__toString());
    }
}
