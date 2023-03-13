<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;

class OrderByCustomerListGetApplicationService
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
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductRepository $productRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductRepository $productRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param OrderByCustomerListGetCommand $command
     * @return OrderListGetResult
     * @throws InvalidArgumentException
     */
    public function handle(OrderByCustomerListGetCommand $command): OrderListGetResult
    {
        $customerId = new CustomerId($command->customerId);
        $customer = $this->customerRepository->findById($customerId);
        if(!$customer) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        [$orders, $pagination] = $this->orderRepository->findAllByCustomer($customerId);

        $orderResults = [];
        foreach ($orders as $order) {
            $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
            $customer = $this->customerRepository->findById($order->getCustomerId());
            $totalCost = 0;
            foreach ($orderProducts as $orderProduct) {
                $totalCost += $orderProduct->getOrderProductCost();
            }
            $orderResults[] = new OrderResult(
                $order->getOrderId()->asString(),
                $order->getCustomerId()->asString(),
                $customer->getCustomerName(),
                '',
                '',
                $order->getOrderDeliveryStatus()->getValue(),
                $order->getOrderPaymentStatus()->getValue(),
                [],
                $order->getUpdatedAt()->asString(),
                $order->getOrderDate()->asString(),
                $totalCost,
                $order->getOrderStatus()
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
