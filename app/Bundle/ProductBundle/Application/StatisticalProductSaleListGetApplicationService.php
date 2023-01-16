<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalDebtCriteria;
use App\Bundle\ProductBundle\Domain\Model\StatisticalProductSaleCriteria;
use http\Exception\InvalidArgumentException;

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
     * @param IProductRepository $productRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     */
    public function __construct(
        ICategoryRepository $categoryRepository,
        IOrderRepository $orderRepository,
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
    }

    /**
     * @param StatisticalProductSaleListGetCommand $command
     * @return StatisticalDebtListGetResult
     */
    public function handle(StatisticalProductSaleListGetCommand $command): StatisticalDebtListGetResult
    {
        $criteria = new StatisticalProductSaleCriteria(
            !is_null($command->categoryId) ? new CategoryId($command->categoryId) : null,
            !is_null($command->productAttributeValueId) ? new ProductAttributeValueId($command->productAttributeValueId) : null,
            !is_null($command->startDate) ? SettingDate::fromTimeStamps($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromTimeStamps($command->endDate) : null,
        );
        $category = $this->categoryRepository->findById();
        if (!$category) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        $orders = $this->orderRepository->findAllByStatistical($criteria);
        $debtResults = [];
        foreach ($debts as $debt) {
            $debtResults[] = new DebtResult(
                $debt->getDebtHistoryId()->asString(),
                '',
                '',
                '',
                '',
                $debt->getTotalDebt(),
                $debt->getTotalPayment(),
                $debt->isCurrent(),
                $debt->getDebtHistoryUpdateType()->getValue(),
                !is_null($debt->getOrderId()) ? $debt->getOrderId()->asString() : null,
                !is_null($debt->getContainerOrderId()) ? $debt->getContainerOrderId()->asString() : null,
                !is_null($debt->getVatId()) ? $debt->getVatId()->asString() : null,
                !is_null($debt->getPaymentId()) ? $debt->getPaymentId()->asString() : null,
                !is_null($debt->getOtherDebtId()) ? $debt->getOtherDebtId()->asString() : null,
                $debt->getNumberOfMoney(),
                $debt->getUpdateDate()->asTimeStamps(),
                $debt->getMonetaryUnitType()->getValue(),
                $debt->getComment(),
                $debt->getVersion()
            );
        }

        return new StatisticalDebtListGetResult($debtResults);
    }
}
