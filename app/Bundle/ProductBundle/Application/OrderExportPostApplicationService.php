<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\OrderCriteria;
use App\Bundle\ProductBundle\Domain\Model\OrderId;

class OrderExportPostApplicationService
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
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     * @param IUserRepository $userRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IProductRepository $productRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IUserRepository $userRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductInventoryRepository $productInventoryRepository,
        IProductRepository $productRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param OrderExportPostCommand $command
     * @return OrderExportPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderExportPostCommand $command): OrderExportPostResult
    {
        $orderId = new OrderId($command->orderId);
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new InvalidArgumentException(MessageConst::NOT_FOUND['message']);
        }

        $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
        $customer = $this->customerRepository->findById($order->getCustomerId());

        $orderProductExportResults = [];
        foreach ($orderProducts as $orderProduct) {
            $productAttributeValue = $this->productAttributeValueRepository->findById($orderProduct->getProductAttributeValueId());
            $product = $this->productRepository->findById($orderProduct->getProductId());
            $orderProductExportResults[] = new OrderProductExportResult(
                $orderProduct->getOrderProductId()->asString(),
                $orderProduct->getOrderId()->asString(),
                $orderProduct->getProductId()->asString(),
                $orderProduct->getProductAttributeValueId()->asString(),
                $orderProduct->getProductAttributePriceId()->asString(),
                $orderProduct->getCount(),
                $productAttributeValue->getValue(),
                $product->getName()
            );
        }

        return new OrderExportPostResult(
            $order->getOrderId()->asString(),
            $order->getCustomerId()->asString(),
            $order->getUserId()->asString(),
            $order->getOrderDeliveryStatus()->getValue(),
            $order->getOrderPaymentStatus()->getValue(),
            $orderProductExportResults,
            $order->getUpdatedAt()->asString(),
            $order->getCreatedAt()->asString(),
            $customer->getCustomerName(),
        );
    }
}
