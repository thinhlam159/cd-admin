<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitId;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValue;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductPutApplicationService
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
     * @var ICategoryRepository
     */
    private ICategoryRepository $categoryRepository;

    /**
     * @param IProductRepository $productRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param ICategoryRepository $customerRepository
     */
    public function __construct(
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository,
        ICategoryRepository $categoryRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param ProductPutCommand $command
     * @return ProductPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductPutCommand $command): ProductPutResult
    {
        $productId = new ProductId($command->productId);
        $existingName = $this->productRepository->checkExistingName($command->name, $productId);
        if ($existingName) {
            throw new InvalidArgumentException('Existing Email!');
        }

        $categoryId = new CategoryId($command->categoryId);
        $measureUnitId = new MeasureUnitId($command->measureUnitId);
        $productAttributeId = new ProductAttributeId($command->productAttributeId);
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
            $productAttributeId,
            $measureUnitId,
            $command->productAttributeValue,
            $command->productAttributeCode
        );

        $productAttributePriceId = ProductAttributePriceId::newId();
        $productAttributePrice = new ProductAttributePrice(
            $productAttributePriceId,
            $productAttributeValueId,
            $command->price,
            MonetaryUnitType::fromValue($command->monetaryUnitId),
            true
        );

        DB::beginTransaction();
        try {
            $productId = $this->productRepository->update($product);
            $productAttributeValueId = $this->productAttributeValueRepository->update($productAttributeValue);
            $productAttributePriceId = $this->productAttributePriceRepository->update($productAttributePrice);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Update product fail!');
        }

        return new ProductPutResult($productId->__toString());
    }
}
