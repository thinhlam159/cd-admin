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
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalCountCustomerOrderCriteria;
use App\Bundle\ProductBundle\Domain\Model\StatisticalCustomerOrderAmountCriteria;
use App\Bundle\ProductBundle\Domain\Model\StatisticalProductSaleCriteria;

class StatisticalCustomerOrderAmountListGetApplicationService
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
     * @param StatisticalCustomerOrderAmountListGetCommand $command
     * @return StatisticalCustomerOrderAmountListGetResult
     */
    public function handle(StatisticalCustomerOrderAmountListGetCommand $command): StatisticalCustomerOrderAmountListGetResult
    {
        $criteria = new StatisticalCustomerOrderAmountCriteria(
            !is_null($command->customerId) ? new CategoryId($command->customerId) : null,
            $command->keyword,
            $command->order,
            $command->sort,
            !is_null($command->startDate) ? SettingDate::fromYmdHis($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromYmdHis($command->endDate) : null,
        );

        $customers = $this->customerRepository->findAllByKeyword($command->keyword);
        $amountResults = [];
        foreach ($customers as $customer) {
            $criteria = new StatisticalCountCustomerOrderCriteria(
                $customer->getCustomerId(),
                SettingDate::fromYmdHis($command->startDate),
                SettingDate::fromYmdHis($command->endDate),
            );
            $customerOrderAmount = $this->orderRepository->countCustomerOrders($criteria);
            $amountResults[] = new StatisticalCustomerOrderAmountResult(
                $customer->getCustomerId()->asString(),
                $customer->getCustomerName(),
                $customerOrderAmount
            );
        }

        return new StatisticalCustomerOrderAmountListGetResult($amountResults);
    }
}
