<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
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

class StatisticalProductSaleListGetApplicationService
{
    /**
     * @var ICategoryRepository
     */
    private ICategoryRepository $categoryRepository;

    /**
     * @var IOrderRepository
     */
    private IOrderRepository $orderRepository;

    /**
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @param ICategoryRepository $categoryRepository
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     * @param IProductRepository $productRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     */
    public function __construct(
        ICategoryRepository $categoryRepository,
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
    }

    /**
     * @param StatisticalCountCustomerOrderGetCommand $command
     * @return StatisticalProductSaleListGetResult
     */
    public function handle(StatisticalCountCustomerOrderGetCommand $command): StatisticalProductSaleListGetResult
    {
        $criteria = new StatisticalCountCustomerOrderCriteria(
            !is_null($command->customerId) ? new CustomerId($command->customerId) : null,
            !is_null($command->startDate) ? SettingDate::fromTimeStamps($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromTimeStamps($command->endDate) : null,
        );
        $category = $this->categoryRepository->findById(new CategoryId($command->categoryId));
        if (!$category) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
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
