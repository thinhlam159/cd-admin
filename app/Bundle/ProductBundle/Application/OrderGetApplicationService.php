<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\OrderCriteria;
use App\Bundle\ProductBundle\Domain\Model\OrderId;

class OrderGetApplicationService
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
     * @var IProductAttributePriceRepository
     */
    private IProductAttributePriceRepository $productAttributePriceRepository;

    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     * @param IUserRepository $userRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductRepository $productRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IUserRepository $userRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductRepository $productRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param OrderGetCommand $command
     * @return OrderGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderGetCommand $command): OrderGetResult
    {
        $orderId = new OrderId($command->orderId);
        $order = $this->orderRepository->findById($orderId);
        if (!$order) {
            throw new InvalidArgumentException(MessageConst::NOT_FOUND['message']);
        }
        $customer = $this->customerRepository->findById($order->getCustomerId());
        $user = $this->userRepository->findById($order->getUserId());


        $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
        $orderProductResults = [];
        $totalCost = 0;
        foreach ($orderProducts as $orderProduct) {
            $productAttributePrice = $this->productAttributePriceRepository->findById($orderProduct->getProductAttributePriceId());
            $productAttributeValue = $this->productAttributeValueRepository->findById($orderProduct->getProductAttributeValueId());
            $product = $this->productRepository->findById($orderProduct->getProductId());
            $totalCost += $orderProduct->getOrderProductCost();
            $orderProductResults[] = new OrderProductResult(
                $orderProduct->getOrderProductId()->asString(),
                $orderProduct->getOrderId()->asString(),
                $orderProduct->getProductId()->asString(),
                $orderProduct->getProductAttributeValueId()->asString(),
                $orderProduct->getProductAttributePriceId()->asString(),
                $orderProduct->getCount(),
                $orderProduct->getMeasureUnitType()->getValue(),
                $orderProduct->getWeight(),
                $orderProduct->getAttributeDisplayIndex(),
                $productAttributePrice->getNoticePriceType()->getValue(),
                $productAttributePrice->getStandardPrice(),
                $orderProduct->getOrderProductCost(),
                $productAttributeValue->getCode(),
                $product->getName(),
                $product->getCode()
            );
        }

        return new OrderGetResult(
            $order->getOrderId()->asString(),
            $order->getCustomerId()->asString(),
            $customer->getCustomerName(),
            $user->getUserName(),
            $order->getUserId()->asString(),
            $order->getOrderDeliveryStatus()->getValue(),
            $order->getOrderPaymentStatus()->getValue(),
            $order->getUpdatedAt()->asString(),
            $totalCost,
            $orderProductResults,
        );
    }
}
