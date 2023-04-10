<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodCriteria;
use App\Bundle\ProductBundle\Domain\Model\IExportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;

class ExportGoodListGetApplicationService
{
    /**
     * @var IExportGoodRepository
     */
    private IExportGoodRepository $exportGoodRepository;

    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @param IExportGoodRepository $exportGoodRepository
     * @param IProductRepository $productRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(
        IExportGoodRepository $exportGoodRepository,
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IUserRepository $userRepository
    )
    {
        $this->exportGoodRepository = $exportGoodRepository;
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ExportGoodListGetCommand $command
     * @return ExportGoodListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ExportGoodListGetCommand $command): ExportGoodListGetResult
    {
        $criteria = new ExportGoodCriteria(
            !is_null($command->productId) ? new ProductId($command->productId) : null,
            !is_null($command->productAttributeValueId) ? new ProductAttributeValueId($command->productAttributeValueId) : null,
            $command->keyword,
            $command->sort,
            $command->order,
            !is_null($command->startDate) ? SettingDate::fromYmdHis($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromYmdHis($command->endDate) : null,
        );
        [$exportGoods, $pagination] = $this->exportGoodRepository->findAll($criteria);
        $exportGoodResults = [];
        foreach ($exportGoods as $exportGood) {
            $exportGoodProducts = $this->exportGoodRepository->findExportGoodProductByExportGoodId($exportGood->getExportGoodId());
            $user = $this->userRepository->findById($exportGood->getUserId());
            $exportGoodProductResults = [];
            foreach ($exportGoodProducts as $exportGoodProduct) {
                $productAttributeValue = $this->productAttributeValueRepository->findById($exportGoodProduct->getProductAttributeValueId());
                $product = $this->productRepository->findById($exportGoodProduct->getProductId());
                $exportGoodProductResults[] = new ExportGoodProductResult(
                    $exportGoodProduct->getExportGoodProductId()->asString(),
                    $exportGoodProduct->getProductId()->asString(),
                    $product->getName(),
                    $product->getCode(),
                    $productAttributeValue->getProductAttributeValueId()->asString(),
                    $productAttributeValue->getProductAttributeName(),
                    $productAttributeValue->getCode(),
                    $exportGoodProduct->getCount(),
                    $productAttributeValue->getMeasureUnitType()->getValue(),
                );
            }
            $exportGoodResults[] = new ExportGoodResult(
                $exportGood->getExportGoodId(),
                $exportGood->getUserId(),
                $user->getUserName(),
                $exportGood->getDate()->asString(),
                $exportGoodProductResults
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new ExportGoodListGetResult($exportGoodResults, $paginationResult);
    }
}
