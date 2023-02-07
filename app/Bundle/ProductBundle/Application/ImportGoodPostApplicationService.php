<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\UserId;
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
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryImportGood;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportGoodPostApplicationService
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
     * @param ImportGoodPostCommand $command
     * @return ImportGoodPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ImportGoodPostCommand $command): ImportGoodPostResult
    {
        $importGoodId = ImportGoodId::newId();
        $importGood = new ImportGood(
            $importGoodId,
            null,
            new UserId($command->userId),
            SettingDate::fromYmdHis($command->date),
            $command->containerName
        );

        $importGoodProducts = [];
        $currentProductInventories= [];
        $newProductInventories = [];
        foreach ($command->importGoodProductCommands as $importGoodProductCommand) {
            $importGoodProductId = ImportGoodProductId::newId();
            $importGoodProducts[] = new ImportGoodProduct(
                $importGoodProductId,
                $importGoodId,
                new ProductId($importGoodProductCommand->productId),
                new ProductAttributeValueId($importGoodProductCommand->productAttributeValueId),
                $importGoodProductCommand->price,
                MonetaryUnitType::fromValue($importGoodProductCommand->monetaryUnitType),
                $importGoodProductCommand->count,
                MeasureUnitType::fromValue($importGoodProductCommand->measureUnitType)
            );
            $productAttributeValueId = new ProductAttributeValueId($importGoodProductCommand->productAttributeValueId);
            $currentProductInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributeValueId);
            $newCount = $currentProductInventory->getCount() + $importGoodProductCommand->count;
            $newProductInventories[] = new ProductInventoryImportGood(
                ProductInventoryId::newId(),
                new ProductAttributeValueId($importGoodProductCommand->productAttributeValueId),
                $newCount,
                MeasureUnitType::fromValue($importGoodProductCommand->measureUnitType),
                ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::IMPORT_GOOD),
                $importGoodProductId,
                $importGoodProductCommand->count,
                true
            );
            $currentProductInventories[] = $currentProductInventory;
        }

        DB::beginTransaction();
        try {
            $result = $this->importGoodRepository->create($importGood);
            if (!$result) {
                throw new InvalidArgumentException('create import good failed!');
            }
            $result = $this->importGoodRepository->createImportGoodProducts($importGoodProducts);
            if (!$result) {
                throw new InvalidArgumentException('create import good product failed!');
            }
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('update product inventory failed!');
            }
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventoryByImportGood($newProductInventories);
            if (!$createInventoryProductResult) {
                throw new InvalidArgumentException('create product inventory failed!');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new ImportGoodPostResult($importGoodId->__toString());
    }
}
