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
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
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
     * @param StatisticalProductSaleListGetCommand $command
     * @return StatisticalProductSaleListGetResult
     */
    public function handle(StatisticalProductSaleListGetCommand $command): StatisticalProductSaleListGetResult
    {
        $criteria = new StatisticalProductSaleCriteria(
            !is_null($command->categoryId) ? new CategoryId($command->categoryId) : null,
            !is_null($command->productAttributeValueId) ? new ProductAttributeValueId($command->productAttributeValueId) : null,
            !is_null($command->startDate) ? SettingDate::fromYmdHis($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromYmdHis($command->endDate) : null,
        );
        $category = $this->categoryRepository->findById(new CategoryId($command->categoryId));
        if (!$category) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        $products = $this->productRepository->findByCategoryId($category->getCategoryId());
        $orders = $this->orderRepository->findAllByProductSale($criteria);
        $productIds = [];
        foreach ($products as $product) {
            $productAttributeValues = $this->productAttributeValueRepository->findByProductId($product->getProductId());
            if (empty($productAttributeValues) || $productAttributeValues[0]->getMeasureUnitType()->getType() != MeasureUnitType::ROLL) continue;
            $productIds[] = $product->getProductId()->asString();
        }
        $numberOfProducts = [];
        foreach ($orders as $order) {
            $orderProducts = $this->orderRepository->findOrderProductsByOrderId($order->getOrderId());
            foreach ($orderProducts as $orderProduct) {
                if (!in_array($orderProduct->getProductId(), $productIds)) continue;
                $numberOfProducts[$orderProduct->getProductId()->asString()] =
                    !empty($numberOfProducts[$orderProduct->getProductId()->asString()])
                        ? $numberOfProducts[$orderProduct->getProductId()->asString()] + $orderProduct->getCount()
                        : $orderProduct->getCount();
            }
        }
        $statisticalProductSaleResults = [];
        foreach ($numberOfProducts as $productId => $numberOfProduct) {
            $product = $this->productRepository->findById(new ProductId($productId));
            $statisticalProductSaleResults[] = new StatisticalProductSaleResult(
                $product->getProductId()->asString(),
                $product->getName(),
                $product->getCode(),
                $numberOfProduct
            );
        }

        return new StatisticalProductSaleListGetResult($statisticalProductSaleResults);
    }
}
