<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IDealerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DealerId;
use App\Bundle\Admin\Domain\Model\DealerId as AdminDealerId;
use App\Bundle\ProductBundle\Domain\Model\IImportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGood;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodCriteria;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodId;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProduct;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProductId;
use App\Bundle\ProductBundle\Domain\Model\IOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryImportGood;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportGoodListGetApplicationService
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
     * @param ImportGoodListGetCommand $command
     * @return ImportGoodListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ImportGoodListGetCommand $command): ImportGoodListGetResult
    {
        $criteria = new ImportGoodCriteria(
            !is_null($command->productId) ? new ProductId($command->productId) : null,
            !is_null($command->dealerId) ? new DealerId($command->dealerId) : null,
            !is_null($command->productAttributeValueId) ? new ProductAttributeValueId($command->productAttributeValueId) : null,
            $command->keyword,
            $command->sort,
            $command->order,
            !is_null($command->startDate) ? SettingDate::fromYmdHis($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromYmdHis($command->endDate) : null,
        );
        [$importGoods, $pagination] = $this->importGoodRepository->findAll($criteria);
        $importGoodResults = [];
        foreach ($importGoods as $importGood) {
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
            $importGoodResults[] = new ImportGoodResult(
                $importGood->getImportGoodId(),
                !is_null($dealer) ? $dealer->getDealerId()->asString() : null,
                !is_null($dealer) ? $dealer->getName() : null,
                $importGood->getUserId(),
                $user->getUserName(),
                'date',
                $importGoodProductResults
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new ImportGoodListGetResult($importGoodResults, $paginationResult);
    }
}
