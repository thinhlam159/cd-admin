<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\ExportGood;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodId;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodProduct;
use App\Bundle\ProductBundle\Domain\Model\ExportGoodProductId;
use App\Bundle\ProductBundle\Domain\Model\IExportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryExportGood;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportGoodPostApplicationService
{
    /**
     * @var IExportGoodRepository
     */
    private IExportGoodRepository $exportGoodRepository;

    /**
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @param IExportGoodRepository $exportGoodRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     */
    public function __construct(
        IExportGoodRepository $exportGoodRepository,
        IProductInventoryRepository $productInventoryRepository,
        IProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->exportGoodRepository = $exportGoodRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
    }

    /**
     * @param ExportGoodPostCommand $command
     * @return ExportGoodPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ExportGoodPostCommand $command): ExportGoodPostResult
    {
        $exportGoodId = ExportGoodId::newId();
        $exportGood = new ExportGood(
            $exportGoodId,
            new UserId($command->userId),
            SettingDate::fromYmdHis($command->date)
        );

        $exportGoodProducts = [];
        $currentProductInventories= [];
        $newProductInventories = [];
        foreach ($command->exportGoodProductCommands as $exportGoodProductCommand) {
            $exportGoodProductId = ExportGoodProductId::newId();
            $productAttributeValueId = new ProductAttributeValueId($exportGoodProductCommand->productAttributeValueId);
            $productAttributeValue = $this->productAttributeValueRepository->findById($productAttributeValueId);
            $exportGoodProducts[] = new ExportGoodProduct(
                $exportGoodProductId,
                $exportGoodId,
                new ProductId($exportGoodProductCommand->productId),
                new ProductAttributeValueId($exportGoodProductCommand->productAttributeValueId),
                $exportGoodProductCommand->count,
                $productAttributeValue->getMeasureUnitType(),
            );
            $currentProductInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributeValueId);
            $newCount = $currentProductInventory->getCount() - $exportGoodProductCommand->count;
            $newProductInventories[] = new ProductInventoryExportGood(
                ProductInventoryId::newId(),
                new ProductAttributeValueId($exportGoodProductCommand->productAttributeValueId),
                $newCount,
                ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::EXPORT_GOOD),
                $exportGoodProductId,
                $exportGoodProductCommand->count,
                true
            );
            $currentProductInventories[] = $currentProductInventory;
        }

        DB::beginTransaction();
        try {
            $result = $this->exportGoodRepository->create($exportGood);
            if (!$result) {
                throw new InvalidArgumentException('create import good failed!');
            }
            $result = $this->exportGoodRepository->createExportGoodProducts($exportGoodProducts);
            if (!$result) {
                throw new InvalidArgumentException('create import good product failed!');
            }
            $updateCurrentInventoryResult = $this->productInventoryRepository->updateProductInventories($currentProductInventories);
            if (!$updateCurrentInventoryResult) {
                throw new InvalidArgumentException('update product inventory failed!');
            }
            $createInventoryProductResult = $this->productInventoryRepository->createMultiProductInventoryByExportGood($newProductInventories);
            if (!$createInventoryProductResult) {
                throw new InvalidArgumentException('create product inventory failed!');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new ExportGoodPostResult($exportGoodId->__toString());
    }
}
