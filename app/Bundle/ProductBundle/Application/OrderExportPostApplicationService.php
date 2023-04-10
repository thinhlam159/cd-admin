<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Constants\DateTimeConst;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
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
        $totalCost = 0;
        foreach ($orderProducts as $orderProduct) {
            $productAttributeValue = $this->productAttributeValueRepository->findById($orderProduct->getProductAttributeValueId());
            $productAttributePrice = $this->productAttributePriceRepository->findById($orderProduct->getProductAttributePriceId());
            $product = $this->productRepository->findById($orderProduct->getProductId());
            $totalCost += $orderProduct->getOrderProductCost();
            $orderProductExportResults[] = new OrderProductExportResult(
                $orderProduct->getOrderProductId()->asString(),
                $orderProduct->getOrderId()->asString(),
                $orderProduct->getProductId()->asString(),
                $orderProduct->getProductAttributeValueId()->asString(),
                $orderProduct->getProductAttributePriceId()->asString(),
                $orderProduct->getCount(),
                $productAttributeValue->getMeasureUnitType()->getValue(),
                $orderProduct->getWeight(),
                $orderProduct->getAttributeDisplayIndex(),
                $orderProduct->getOrderProductCost(),
                $product->getName(),
                $product->getCode(),
                $productAttributeValue->getCode(),
                $productAttributePrice->getNoticePriceType()->getValue(),
                $productAttributePrice->getPrice(),
                $productAttributePrice->getStandardPrice(),
                $orderProduct->getNoteName()
            );
        }
        $orderDay = $order->getOrderDate()->getValue()->format(DateTimeConst::FORMAT_DAY);
        $orderMon = $order->getOrderDate()->getValue()->format(DateTimeConst::FORMAT_MON);
        $orderYear = $order->getOrderDate()->getValue()->format(DateTimeConst::FORMAT_Y);
        $orderDate = "Ngày $orderDay tháng $orderMon năm $orderYear";

        return new OrderExportPostResult(
            $order->getOrderId()->asString(),
            $order->getCustomerId()->asString(),
            $order->getUserId()->asString(),
            $order->getOrderDeliveryStatus()->getValue(),
            $order->getOrderPaymentStatus()->getValue(),
            $orderProductExportResults,
            $order->getUpdatedAt()->asString(),
            $order->getCreatedAt()->asString(),
            $orderDate,
            $customer->getCustomerName(),
            $customer->getPhone(),
            $customer->getAddress(),
            $totalCost
        );
    }
}
