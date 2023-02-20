<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IMeasureUnitRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MeasureUnitType;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductGetApplicationService
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
     * @var IProductAttributeRepository
     */
    private IProductAttributeRepository $productAttributeRepository;

    /**
     * @var IMeasureUnitRepository
     */
    private IMeasureUnitRepository $measureUnitRepository;

    /**
     * @param IProductRepository $productRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param ICategoryRepository $categoryRepository
     * @param IProductAttributeRepository $productAttributeRepository
     * @param IMeasureUnitRepository $measureUnitRepository
     */
    public function __construct(
        IProductRepository $productRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository,
        ICategoryRepository $categoryRepository,
        IProductAttributeRepository $productAttributeRepository,
        IMeasureUnitRepository $measureUnitRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->measureUnitRepository = $measureUnitRepository;
    }

    /**
     * @param ProductGetCommand $command
     * @return ProductGetResult
     */
    public function handle(ProductGetCommand $command): ProductGetResult
    {
        $product = $this->productRepository->findById(new ProductId($command->productId));
        if (!$product) {
            throw new InvalidArgumentException('Record not found!');
        }
        $category = $this->categoryRepository->findById($product->getCategoryId());
        $productAttributeValues = $this->productAttributeValueRepository->findByProductId($product->getProductId());

        $productAttributeValueResults = [];
        foreach ($productAttributeValues as $productAttributeValue) {
            $productAttributePrice = $this->productAttributePriceRepository->findByProductAttributeValueId($productAttributeValue->getProductAttributeValueId());
            $productInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributePrice->getProductAttributeValueId());
            $productAttribute = $this->productAttributeRepository->findById($productAttributeValue->getProductAttributeId());

            $productAttributeValueResults[] = new ProductAttributeValueResult(
                $productAttributeValue->getProductAttributeValueId()->asString(),
                $productAttributeValue->getProductId()->asString(),
                $productAttribute->getName(),
                $productAttributeValue->getValue(),
                $productAttributeValue->getCode(),
                $productInventory->getMeasureUnitType()->getValue(),
                $productInventory->getCount(),
                $productAttributePrice->getPrice(),
                $productAttributePrice->getMonetaryUnitType()->getValue(),
                $productAttributePrice->getNoticePriceType()->getValue(),
                $productAttributePrice->getProductAttributePriceId()->asString(),
                $productAttributePrice->getStandardPrice(),
            );
        }

        return new ProductGetResult(
            $product->getProductId()->asString(),
            $product->getName(),
            $product->getCode(),
            $product->getDescription(),
            $product->getCategoryId()->__toString(),
            $category->getName(),
            $productAttributeValueResults
        );
    }
}
