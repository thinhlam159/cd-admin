<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
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
     * @param OrderListGetCommand $command
     * @return OrderListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(OrderListGetCommand $command): OrderListGetResult
    {
        $orderCriteria = new OrderCriteria();
        [$orders, $pagination] = $this->orderRepository->findAll($orderCriteria);

        $orderResults = [];
        foreach ($orders as $order) {
            $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
            $orderProductResults = [];
            foreach ($orderProducts as $orderProduct) {
                $orderProductResults[] = new OrderProductResult(
                    $orderProduct->getOrderProductId()->asString(),
                    $orderProduct->getOrderId()->asString(),
                    $orderProduct->getProductId()->asString(),
                    $orderProduct->getProductAttributeValueId()->asString(),
                    $orderProduct->getProductAttributePriceId()->asString(),
                    $orderProduct->getCount(),
                );
            }
            $orderResults[] = new OrderResult(
                $order->getOrderId()->asString(),
                $order->getCustomerId()->asString(),
                $order->getUserId()->asString(),
                $order->getOrderDeliveryStatus()->getValue(),
                $order->getOrderPaymentStatus()->getValue(),
                $orderProductResults,
                $order->getUpdateAt()->asString(),
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
