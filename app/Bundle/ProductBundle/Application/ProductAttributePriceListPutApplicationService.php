<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\NoticePriceType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueCriteria;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductAttributePriceListPutApplicationService
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
     * @var IProductAttributeRepository
     */
    private IProductAttributeRepository $productAttributeRepository;

    /**
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IProductAttributeRepository $productAttributeRepository
     */
    public function __construct(
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository,
        IProductAttributeRepository $productAttributeRepository
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->productAttributeRepository = $productAttributeRepository;
    }

    /**
     * @param ProductAttributePriceListPutCommand $command
     * @return ProductAttributePriceListPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductAttributePriceListPutCommand $command): ProductAttributePriceListPutResult
    {
        $oldPrices = [];
        $newPrices = [];
        foreach ($command->productAttributePriceCommands as $priceCommand) {
            $newPrices = new ProductAttributePrice(
                ProductAttributePriceId::newId(),
                new ProductAttributeValueId($priceCommand->productAttributeValueId),
                $priceCommand->price,
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                NoticePriceType::fromValue($priceCommand->noticePriceType),
                true
            );
            $oldPrices = new ProductAttributePrice(
                new ProductAttributePriceId($priceCommand->productAttributePriceId),
                new ProductAttributeValueId($priceCommand->productAttributeValueId),
                $priceCommand->price,
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                NoticePriceType::fromValue($priceCommand->noticePriceType),
                false
            );
        }

        DB::beginTransaction();
        try {
            $createIds = $this->productAttributePriceRepository->createMany($newPrices);
            $updateIds = $this->productAttributePriceRepository->updateMany($oldPrices);
            if (empty($createIds) || empty($updateIds)) {
                throw new Exception();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('update product price fail!');
        }

        return new ProductAttributePriceListPutResult();
    }
}
