<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
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
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductRepository $productRepository
     */
    public function __construct(
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductRepository $productRepository
    )
    {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productRepository = $productRepository;

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
            $productId = new ProductId($priceCommand->productId);
            $product = $this->productRepository->findById(new ProductId($priceCommand->productId));
            if (!$product) {
                throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
            }
            $productAttributeValues = $this->productAttributeValueRepository->findByProductId($productId);
            foreach ($productAttributeValues as $productAttributeValue) {
                $productAttributePriceCurrent = $this->productAttributePriceRepository->findByProductAttributeValueId($productAttributeValue->getProductAttributeValueId());
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
