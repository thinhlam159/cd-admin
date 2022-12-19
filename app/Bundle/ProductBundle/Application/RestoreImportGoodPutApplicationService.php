<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DealerId;
use App\Bundle\ProductBundle\Domain\Model\IImportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGood;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodId;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProduct;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodProductId;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventory;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryImportGood;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestoreImportGoodPutApplicationService
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
     * @param IImportGoodRepository $importGoodRepository
     * @param IProductInventoryRepository $productInventoryRepository
     */
    public function __construct(
        IImportGoodRepository $importGoodRepository,
        IProductInventoryRepository $productInventoryRepository
    )
    {
        $this->importGoodRepository = $importGoodRepository;
        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * @param RestoreImportGoodPutCommand $command
     * @return RestoreImportGoodPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(RestoreImportGoodPutCommand $command): RestoreImportGoodPutResult
    {
        $importGoodId = new ImportGoodId($command->importGoodId);
        $importGood = $this->importGoodRepository->findById($importGoodId);
        if (!$importGood) {
            throw new InvalidArgumentException(MessageConst::NOT_FOUND['message']);
        }

        $restoreImportGoodProducts = $this->importGoodRepository->findImportGoodProductByImportGoodId($importGoodId);

        $importGoodProducts = [];
        $currentProductInventories= [];
        $newProductInventories = [];
        foreach ($restoreImportGoodProducts as $restoreImportGoodProduct) {
            $productAttributeValueId = new ProductAttributeValueId($restoreImportGoodProduct->getProductAttributeValueId());
            $currentProductInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributeValueId);
            $newCount = $currentProductInventory->getCount() - $restoreImportGoodProduct->getCount();
            $newProductInventories[] = new ProductInventoryImportGood(
                ProductInventoryId::newId(),
                $productAttributeValueId,
                $newCount,
                MeasureUnitType::fromValue($restoreImportGoodProduct->getMeasureUnitType()->getValue()),
                ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::RESTORE_IMPORT_GOOD),
                $restoreImportGoodProduct->getImportGoodProductId(),
                $restoreImportGoodProduct->getCount(),
                true
            );
            $currentProductInventories[] = $currentProductInventory;
        }

        DB::beginTransaction();
        try {
            $result = $this->importGoodRepository->restoreImportGood($importGoodId);
            if (!$result) {
                throw new InvalidArgumentException('update delivery status failed!');
            }

            $result = $this->importGoodRepository->createImportGoodProducts($importGoodProducts);
            if (!$result) {
                throw new InvalidArgumentException('update delivery status failed!');
            }

            //restore old inventory
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('customer not exist!');
            }
            //create new inventory
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventoryByImportGood($newProductInventories);
            if (!$createInventoryProductResult) {
                throw new InvalidArgumentException('customer not exist!');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new RestoreImportGoodPutResult($importGoodId->__toString());
    }
}
