<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalCountCustomerOrderCriteria;
use App\Bundle\ProductBundle\Domain\Model\StatisticalProductSaleCriteria;

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
     * @param StatisticalProductSaleListGetCommand $command
     * @return StatisticalProductSaleListGetResult
     */
    public function handle(StatisticalProductSaleListGetCommand $command): StatisticalProductSaleListGetResult
    {
        $criteria = new StatisticalCountCustomerOrderCriteria(
            !is_null($command->categoryId) ? new CategoryId($command->categoryId) : null,
            !is_null($command->startDate) ? SettingDate::fromTimeStamps($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromTimeStamps($command->endDate) : null,
        );

        $products = $this->productRepository->findByCategoryId($category->getCategoryId());
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
