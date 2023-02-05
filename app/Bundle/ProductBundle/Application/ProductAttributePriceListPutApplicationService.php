<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
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
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository

     */
    public function __construct(
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;

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
            $productAttributeValue = $this->productAttributeValueRepository->findById(new ProductAttributeValueId($priceCommand->productAttributeValueId));
            $productAttributePriceCurrent = $this->productAttributePriceRepository->findById(new ProductAttributePriceId($priceCommand->productAttributePriceId));
            $newPrices[] = new ProductAttributePrice(
                ProductAttributePriceId::newId(),
                $productAttributeValue->getProductAttributeValueId(),
                $priceCommand->price,
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                $productAttributePriceCurrent->getNoticePriceType(),
                true
            );
            $oldPrices[] = new ProductAttributePrice(
                $productAttributePriceCurrent->getProductAttributePriceId(),
                new ProductAttributeValueId($priceCommand->productAttributeValueId),
                $productAttributePriceCurrent->getPrice(),
                MonetaryUnitType::fromType(MonetaryUnitType::VND),
                $productAttributePriceCurrent->getNoticePriceType(),
                false
            );
        }

        DB::beginTransaction();
        try {
            $createIds = $this->productAttributePriceRepository->createMany($newPrices);
            $updateResult = $this->productAttributePriceRepository->updateOldPrice($oldPrices);
            if (empty($createIds) || !$updateResult) {
                throw new Exception();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new ProductAttributePriceListPutResult();
    }
}
