<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\DealerId as AdminDealerId;
use App\Bundle\Admin\Domain\Model\IDealerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IImportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodId;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;

class ImportGoodGetApplicationService
{
    /**
     * @var IImportGoodRepository
     */
    private IImportGoodRepository $importGoodRepository;

    /**
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @var IDealerRepository
     */
    private IDealerRepository $dealerRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @param IImportGoodRepository $importGoodRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductRepository $productRepository
     * @param IDealerRepository $dealerRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(
        IImportGoodRepository $importGoodRepository,
        IProductInventoryRepository $productInventoryRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductRepository $productRepository,
        IDealerRepository $dealerRepository,
        IUserRepository $userRepository
    )
    {
        $this->importGoodRepository = $importGoodRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productRepository = $productRepository;
        $this->dealerRepository = $dealerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ImportGoodGetCommand $command
     * @return ImportGoodGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ImportGoodGetCommand $command): ImportGoodGetResult
    {
        $importGood = $this->importGoodRepository->findById(new ImportGoodId($command->importGoodId));
            $importGoodProducts = $this->importGoodRepository->findImportGoodProductByImportGoodId($importGood->getImportGoodId());
            $dealer = !is_null($importGood->getDealerId()) ? $this->dealerRepository->findById(new AdminDealerId($importGood->getDealerId()->asString())) : null;
            $user = $this->userRepository->findById($importGood->getUserId());
            $importGoodProductResults = [];
            foreach ($importGoodProducts as $importGoodProduct) {
                $productAttributeValue = $this->productAttributeValueRepository->findById($importGoodProduct->getProductAttributeValueId());
                $product = $this->productRepository->findById($importGoodProduct->getProductId());
                $importGoodProductResults[] = new ImportGoodProductResult(
                    $importGoodProduct->getImportGoodProductId()->asString(),
                    $importGoodProduct->getProductId()->asString(),
                    $product->getName(),
                    $product->getCode(),
                    $productAttributeValue->getProductAttributeValueId()->asString(),
                    $productAttributeValue->getProductAttributeName(),
                    $productAttributeValue->getCode(),
                    $importGoodProduct->getPrice(),
                    $importGoodProduct->getMonetaryUnitType()->getValue(),
                    $importGoodProduct->getCount(),
                    $importGoodProduct->getMeasureUnitType()->getValue(),
                );
            }
        return new ImportGoodGetResult(
                $importGood->getImportGoodId(),
                !is_null($dealer) ? $dealer->getDealerId()->asString() : null,
                !is_null($dealer) ? $dealer->getName() : null,
                $importGood->getUserId(),
                $user->getUserName(),
                $importGood->getDate()->asString(),
                $importGood->getContainerName(),
                $importGoodProductResults
            );
    }
}
