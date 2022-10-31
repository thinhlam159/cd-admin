<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
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
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @param IProductRepository $productRepository
     */
    public function __construct(
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository,
        ICustomerRepository $customerRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param ProductPostCommand $command
     * @return ProductPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductPostCommand $command): ProductPostResult
    {
//        $existingEmail = $this->categoryRepository->checkExistingEmail($command->email);
//        if ($existingEmail) {
//            throw new InvalidArgumentException('Existing Email!');
//        }
        $productId = ProductId::newId();
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
            $productId = $this->productRepository->create($product);
            $productAttributeValueId = $this->productAttributeValueRepository->create($productAttributeValue);
            $productAttributePriceId = $this->productAttributePriceRepository->create($productAttributePrice);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new ProductPostResult($productId->__toString());
    }
}
