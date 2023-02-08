<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
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
use App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType;
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
     * @var IProductAttributePriceRepository
     */
    private IProductAttributePriceRepository $productAttributePriceRepository;

    /**
     * @var IProductInventoryRepository
     */
    private IProductInventoryRepository $productInventoryRepository;

    /**
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     */
    public function __construct(
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
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
            MeasureUnitType::fromValue($command->measureUnitType),
            ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::INITIALIZATION),
            true
        );

        $productAttributePrice = new ProductAttributePrice(
            ProductAttributePriceId::newId(),
            $productAttributeValueId,
            $command->price,
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            NoticePriceType::fromValue($command->noticePriceType),
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
            throw new TransactionException($e->getMessage());
        }

        return new ProductAttributeValuePostResult($productAttributeValueId->__toString());
    }
}
