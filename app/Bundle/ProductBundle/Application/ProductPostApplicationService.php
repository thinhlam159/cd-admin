<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\NoticePriceType;
use App\Bundle\ProductBundle\Domain\Model\Product;
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

class ProductPostApplicationService
{
    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

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
     * @param IProductRepository $productRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     */
    public function __construct(
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * @param ProductPostCommand $command
     * @return ProductPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductPostCommand $command): ProductPostResult
    {
        $productId = ProductId::newId();
        $categoryId = new CategoryId($command->categoryId);
        $product = new Product(
            $productId,
            $command->name,
            $command->code,
            $command->description,
            $categoryId,
        );
        $productAttributeValueId = ProductAttributeValueId::newId();
        $productAttributeValue = new ProductAttributeValue(
            $productAttributeValueId,
            $productId,
            new ProductAttributeId('01GF2WV4414FAS2RMP4CY8BQBG'),
            '',
            '',
            null,
            null,
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

        $productInventory = new ProductInventory(
            ProductInventoryId::newId(),
            $productAttributeValueId,
            0,
            MeasureUnitType::fromValue($command->measureUnitType),
            ProductInventoryUpdateType::fromType(ProductInventoryUpdateType::INITIALIZATION),
            true
        );

        DB::beginTransaction();
        try {
            $productId = $this->productRepository->create($product);
            $productAttributeValueId = $this->productAttributeValueRepository->create($productAttributeValue);
            $productAttributePriceId = $this->productAttributePriceRepository->create($productAttributePrice);
            $productInventoryId = $this->productInventoryRepository->create($productInventory);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new ProductPostResult($productId->__toString());
    }
}
