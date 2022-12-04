<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\Order;
use App\Bundle\ProductBundle\Domain\Model\OrderDeliveryStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderProduct;
use App\Bundle\ProductBundle\Domain\Model\OrderProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryOrder;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderPostApplicationService
{
    /**
     * @var IOrderRepository
     */
    private IOrderRepository $orderRepository;

    /**
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     * @param IUserRepository $userRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductInventoryRepository $productInventoryRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IUserRepository $userRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductInventoryRepository $productInventoryRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * @param OrderPostCommand $command
     * @return OrderPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderPostCommand $command): OrderPostResult
    {
        $orderId = OrderId::newId();
        $customerId = new CustomerId($command->customerId);
        $userId = new UserId($command->userId);

        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            throw new InvalidArgumentException('customer not exist!');
        }
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new InvalidArgumentException('user not exist!');
        }

        $order = new Order(
            $orderId,
            $customerId,
            $userId,
            OrderDeliveryStatus::fromStatus(OrderDeliveryStatus::IN_PROGRESS),
            OrderPaymentStatus::fromStatus(OrderPaymentStatus::PLANNING),
        );

        $orderProducts = [];
        $newProductInventories = [];
        foreach ($command->orderProductCommands as $orderProductCommand) {
            $productAttributeValueId = $orderProductCommand->productAttributeValueId;
            $orderProducts[] = new OrderProduct(
                OrderProductId::newId(),
                $orderId,
                new ProductId($orderProductCommand->productId),
                new ProductAttributeValueId($productAttributeValueId),
                new ProductAttributePriceId($orderProductCommand->productAttributePriceId),
                $orderProductCommand->count,
                $orderProductCommand->attributeDisplayIndex,
                $orderProductCommand->weight,
            );

            $newProductInventories[$productAttributeValueId]['count'] =
                isset($newProductInventories[$productAttributeValueId]['count'])
            ? $newProductInventories[$productAttributeValueId]['count'] += $orderProductCommand->count
            : $orderProductCommand->count;
            $newProductInventories[$productAttributeValueId]['measure_unit_type'] = $orderProductCommand->measureUnitType;
        }

        $saveNewProductInventories = [];
        $currentProductInventories= [];
        foreach ($newProductInventories as $key => $newProductInventory) {
            $productAttributeValueId = new ProductAttributeValueId($key);
            $currentProductInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributeValueId);
            $newCount = $currentProductInventory->getCount() - $newProductInventory['count'];
            $saveNewProductInventories[] = new ProductInventoryOrder(
                ProductInventoryId::newId(),
                $productAttributeValueId,
                $newCount,
                MeasureUnitType::fromValue($newProductInventory['measure_unit_type']),
                ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::ORDER),
                $orderId,
                $newProductInventory['count'],
                true,
            );
            $currentProductInventories[] = $currentProductInventory;
        }

        DB::beginTransaction();
        try {
            $orderId = $this->orderRepository->create($order);
            if (!$orderId) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventoryByOrder($saveNewProductInventories);
            if (!$createInventoryProductResult) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $result = $this->orderRepository->createOrderProducts($orderProducts);
            if (!$result) {
                throw new InvalidArgumentException('customer not exist!');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new OrderPostResult($orderId->__toString());
    }
}
