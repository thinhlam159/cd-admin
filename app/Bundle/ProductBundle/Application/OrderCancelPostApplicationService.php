<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OrderStatus;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryOrder;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
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
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @param IOrderRepository $orderRepository
     * @param IProductInventoryRepository $productInventoryRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        IProductInventoryRepository $productInventoryRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->productInventoryRepository = $productInventoryRepository;
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
        if ($order->getOrderStatus()->getStatus() !== OrderStatus::RESOLVED) {
            throw new InvalidArgumentException(MessageConst::INVALID_ORDER_STATUS['message']);
        }
        $userId = new UserId($command->userId);
        $order->setUserId($userId);
        $order->updateCancelStatus();

        $orderProducts = $this->orderRepository->findOrderProductsByOrderId($orderId);
        $saveNewProductInventories = [];
        $currentProductInventories = [];
        $countProductItem = [];
        foreach ($orderProducts as $orderProduct) {
            $productAttributeValueId = $orderProduct->getProductAttributeValueId();
            $currentProductInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributeValueId);
            if (array_key_exists("$productAttributeValueId", $countProductItem)) {
                $countProductItem["$productAttributeValueId"] += $orderProduct->getCount();
            } else {
                $countProductItem["$productAttributeValueId"] = $orderProduct->getCount();
            }
            $newCount = $currentProductInventory->getCount() + $countProductItem["$productAttributeValueId"];
            $saveNewProductInventories["$productAttributeValueId"] = new ProductInventoryOrder(
                ProductInventoryId::newId(),
                $productAttributeValueId,
                $newCount,
                $currentProductInventory->getMeasureUnitType(),
                ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::RESTORE_ORDER),
                $orderProduct->getOrderProductId(),
                $countProductItem["$productAttributeValueId"],
                true,
            );
            $currentProductInventories["$productAttributeValueId"] = $currentProductInventory;
        }

        DB::beginTransaction();
        try {
            $result = $this->orderRepository->updateCancelStatus($order);
            if (!$result) {
                throw new InvalidArgumentException('Cập nhật trạng thái không thành công!');
            }
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventoryByOrder($saveNewProductInventories);
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$createInventoryProductResult || !$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('Cập nhật lưu kho thất bại!');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new OrderCancelPostResult($orderId->__toString());
    }
}
