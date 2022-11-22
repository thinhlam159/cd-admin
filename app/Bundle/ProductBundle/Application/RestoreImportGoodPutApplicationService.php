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
use App\Bundle\ProductBundle\Domain\Model\ProductInventory;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
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
            throw new \GuzzleHttp\Exception\InvalidArgumentException();
        }

        $currentImportGoodProducts = $this->importGoodRepository->findImportGoodProductByImportGoodId($importGoodId);

        $importGoodProducts = [];
        $currentProductInventories= [];
        $newProductInventories = [];
        foreach ($command->importGoodProductCommands as $importGoodProductCommand) {
            $importGoodProducts[] = new ImportGoodProduct(
                ImportGoodProductId::newId(),
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
            $newProductInventories[] = new ProductInventory(
                ProductInventoryId::newId(),
                new ProductAttributeValueId($importGoodProductCommand->productAttributeValueId),
                $newCount,
                MeasureUnitType::fromValue($importGoodProductCommand->measureUnitType),
                true
            );
            $currentProductInventories[] = $currentProductInventory;
        }

        DB::beginTransaction();
        try {
            $result = $this->importGoodRepository->create($importGood);
            if (!$result) {
                throw new InvalidArgumentException('update delivery status failed!');
            }
            $result = $this->importGoodRepository->createImportGoodProducts($importGoodProducts);
            if (!$result) {
                throw new InvalidArgumentException('update delivery status failed!');
            }
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('customer not exist!');
            }
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventory($newProductInventories);
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
