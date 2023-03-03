<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\ProductBundle\Domain\Model\NoticePriceType;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId as ProductBundleUserId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\Order;
use App\Bundle\ProductBundle\Domain\Model\OrderDeliveryStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderId;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\OrderProduct;
use App\Bundle\ProductBundle\Domain\Model\OrderProductId;
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
     * @var IProductAttributePriceRepository
     */
    private IProductAttributePriceRepository $productAttributePriceRepository;

    /**
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     * @param IUserRepository $userRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IUserRepository $userRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository,
        IDebtHistoryRepository $debtHistoryRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->debtHistoryRepository = $debtHistoryRepository;
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
            SettingDate::fromYmdHis($command->date)
        );

        $orderProducts = [];
        $newProductInventories = [];
        $totalOrderCost = 0;
        foreach ($command->orderProductCommands as $orderProductCommand) {
            $productAttributeValueId = $orderProductCommand->productAttributeValueId;
            $productAttributePrice = $this->productAttributePriceRepository->findByProductAttributeValueId(new ProductAttributeValueId($productAttributeValueId));
            $orderProductCost = $productAttributePrice->getStandardPrice() * $orderProductCommand->weight;
            $totalOrderCost += $orderProductCost;
            $orderProduct = OrderProductId::newId();
            $amount = $orderProductCommand->count;
            if($orderProductCommand->noticePriceType === NoticePriceType::fromType(NoticePriceType::DEFAULT)->getValue()) {
                $amount = $orderProductCommand->weight;
            }
            $orderProducts[] = new OrderProduct(
                $orderProduct,
                $orderId,
                new ProductId($orderProductCommand->productId),
                new ProductAttributeValueId($productAttributeValueId),
                $productAttributePrice->getProductAttributePriceId(),
                $orderProductCommand->count,
                MeasureUnitType::fromValue($orderProductCommand->measureUnitType),
                $orderProductCommand->attributeDisplayIndex,
                $orderProductCommand->weight,
                $orderProductCost
            );

            $newProductInventories[$productAttributeValueId]['count'] =
                isset($newProductInventories[$productAttributeValueId]['count'])
            ? $newProductInventories[$productAttributeValueId]['count'] += $amount
            : $amount;
            $newProductInventories[$productAttributeValueId]['measure_unit_type'] = $orderProductCommand->measureUnitType;
            $newProductInventories[$productAttributeValueId]['order_product_id'] = $orderProduct;
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
                new OrderProductId($newProductInventory['order_product_id']),
                $newProductInventory['count'],
                true,
            );
            $currentProductInventories[] = $currentProductInventory;
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
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::CONTAINER_ORDER),
            $orderId,
            null,
            null,
            null,
            null,
            $totalOrderCost,
            SettingDate::fromYmdHis($command->date),
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
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $result = $this->orderRepository->createOrderProducts($orderProducts);
            if (!$result) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventoryByOrder($saveNewProductInventories);
            if (!$createInventoryProductResult) {
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
