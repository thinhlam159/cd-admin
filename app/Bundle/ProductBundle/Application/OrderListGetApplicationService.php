<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\OrderCriteria;

class OrderListGetApplicationService
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
     * @param OrderListGetCommand $command
     * @return OrderListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderListGetCommand $command): OrderListGetResult
    {
        $orderCriteria = new OrderCriteria($command->keyword);
        [$orders, $pagination] = $this->orderRepository->findAll($orderCriteria);

        $orderResults = [];
        foreach ($orders as $order) {
            $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
            $customer = $this->customerRepository->findById($order->getCustomerId());
            $user = $this->userRepository->findById($order->getUserId());
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
                    $productAttributeValue->getMeasureUnitType()->getValue(),
                    $orderProduct->getWeight(),
                    $orderProduct->getAttributeDisplayIndex(),
                    $productAttributePrice->getNoticePriceType()->getValue(),
                    $productAttributePrice->getPrice(),
                    $orderProduct->getOrderProductCost(),
                    $productAttributeValue->getCode(),
                    $product->getName(),
                    $product->getCode()
                );
            }
            $orderResults[] = new OrderResult(
                $order->getOrderId()->asString(),
                $order->getCustomerId()->asString(),
                $customer->getCustomerName(),
                $order->getUserId()->asString(),
                $user->getUserName(),
                $order->getOrderDeliveryStatus()->getValue(),
                $order->getOrderPaymentStatus()->getValue(),
                $orderProductResults,
                $order->getUpdatedAt()->asString(),
                $order->getOrderDate()->asString(),
                $totalCost
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new OrderListGetResult(
            $orderResults,
            $paginationResult
        );
    }
}
