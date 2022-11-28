<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\FeatureImagePath;
use App\Bundle\ProductBundle\Domain\Model\FeatureImagePathId;
use App\Bundle\ProductBundle\Domain\Model\IFeatureImagePathRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitId;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\NoticePriceType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValue;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\ProductInventory;
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductAttributeValuePostApplicationService
{
    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @var IFeatureImagePathRepository
     */
    private IFeatureImagePathRepository $featureImagePathRepository;

    /**
     * @var IProductAttributePriceRepository
     */
    private IProductAttributePriceRepository $productAttributePriceRepository;

    /**
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IFeatureImagePathRepository $featureImagePathRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     */
    public function __construct(
        IProductAttributeValueRepository $productAttributeValueRepository,
        IFeatureImagePathRepository $featureImagePathRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->featureImagePathRepository = $featureImagePathRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * @param ProductAttributeValuePostCommand $command
     * @return ProductAttributeValuePostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductAttributeValuePostCommand $command): ProductAttributeValuePostResult
    {
        $productAttributeValueId = ProductAttributeValueId::newId();
        $productId = new ProductId($command->productId);
        $productAttributeId = new ProductAttributeId($command->productAttributeId);
        $measureUnitId = new MeasureUnitId($command->measureUnitId);

        $productAttributeValue = new ProductAttributeValue(
            $productAttributeValueId,
            $productId,
            $productAttributeId,
            $command->value,
            $command->code,
            null,
            null
        );

        $productInventory = new ProductInventory(
            ProductInventoryId::newId(),
            $productAttributeValueId,
            $command->count,
            MeasureUnitType::fromValue($command->measureUnitId),
            true
        );

        $productAttributePrice = new ProductAttributePrice(
            ProductAttributePriceId::newId(),
            $productAttributeValueId,
            $command->price,
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            NoticePriceType::fromValue(NoticePriceType::KG298),
            true
        );

        DB::beginTransaction();
        try {
            $productAttributeValueId = $this->productAttributeValueRepository->create($productAttributeValue);
            $productInventoryId = $this->productInventoryRepository->create($productInventory);
            $productAttributePriceId = $this->productAttributePriceRepository->create($productAttributePrice);
            if (!$productAttributeValueId || !$productInventoryId || !$productAttributePriceId) {
                throw new Exception();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new ProductAttributeValuePostResult($productAttributeValueId->__toString());
    }
}
