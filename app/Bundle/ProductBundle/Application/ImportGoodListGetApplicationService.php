<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IDealerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
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
     * @param IImportGoodRepository $importGoodRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductRepository $productRepository
     * @param IDealerRepository $dealerRepository
     */
    public function __construct(IImportGoodRepository $importGoodRepository, IProductInventoryRepository $productInventoryRepository, IProductAttributeValueRepository $productAttributeValueRepository, IProductRepository $productRepository, IDealerRepository $dealerRepository)
    {
        $this->importGoodRepository = $importGoodRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productRepository = $productRepository;
        $this->dealerRepository = $dealerRepository;
    }

    /**
     * @param ImportGoodListGetCommand $command
     * @return ImportGoodPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ImportGoodListGetCommand $command): ImportGoodPostResult
    {
        $criteria = new ImportGoodCriteria(
            new ProductId($command->productId),
            new DealerId($command->dealerId),
            new ProductAttributeValueId($command->productAttributeValueId),
            $command->keyword,
            $command->sort,
            $command->order,
            SettingDate::fromYmdHis($command->startDate),
            SettingDate::fromYmdHis($command->endDate),
        );
        [$importGoods, $pagination] = $this->importGoodRepository->findAll($criteria);
        foreach ($importGoods as $importGood) {
            $importGoodProducts = $this->importGoodRepository->findImportGoodProductByImportGoodId($importGood->getProductId());
            $dealer = $this->dealerRepository->findById(new AdminDealerId($importGood->getDealerId()->asString()));
            $importGoodProductResults = [];
            foreach ($importGoodProducts as $importGoodProduct) {
                $productAttributeValue = $this->productAttributeValueRepository->findById($importGoodProduct->getProductAttributeValueId());
                $product = $this->productRepository->findById($importGoodProduct->getProductId());
                $importGoodProductResults[] = new ImportGoodProductResult(
                    $importGoodProduct->getProductId()->asString(),
                );
            }
        }

        return new ImportGoodPostResult($importGoodId->__toString());
    }
}
