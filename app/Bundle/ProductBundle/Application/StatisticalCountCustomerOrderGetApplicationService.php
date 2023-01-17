<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;

class StatisticalCountCustomerOrderGetApplicationService
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
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param StatisticalCountCustomerOrderGetCommand $command
     * @return StatisticalProductSaleListGetResult
     */
    public function handle(StatisticalCountCustomerOrderGetCommand $command): StatisticalProductSaleListGetResult
    {
        $customer = $this->customerRepository->findById(new CustomerId($command->customerId));
        if (!$customer) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        $criteria = new StatisticalCountCustomerOrderGetCommand(
            $command->customerId,
            !is_null($command->startDate) ? SettingDate::fromTimeStamps($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromTimeStamps($command->endDate) : null,
        );

        $orders = $this->orderRepository->findAllByProductSale($criteria);
        $orderResults = [];
        $productIds = [];
        foreach ($products as $product) {
            $productIds[] = $product->getProductId()->asString();
        }
        foreach ($orders as $order) {
            $customer = $this->customerRepository->findById($order->getCustomerId());
            $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
            $orderProductResults = [];
            foreach ($orderProducts as $orderProduct) {
                if (!in_array($orderProduct->getProductId(), $productIds)) continue;
                $orderProductResults[] = new OrderProductResult(
                    $orderProduct->getOrderProductId()->asString(),
                    $orderProduct->getOrderId()->asString(),
                    $orderProduct->getProductId()->asString(),
                    $orderProduct->getProductAttributeValueId()->asString(),
                    '',
                    $orderProduct->getCount(),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                );
            }
            $orderResults[] = new OrderResult(
                $order->getOrderId()->asString(),
                $order->getCustomerId()->asString(),
                $customer->getCustomerName(),
                '',
                '',
                $order->getOrderDeliveryStatus(),
                $order->getOrderPaymentStatus(),
                $orderProductResults,
                '',
                $order->getOrderDate()->asString(),
                '',
            );
        }

        return new StatisticalProductSaleListGetResult($orderResults);
    }
}
