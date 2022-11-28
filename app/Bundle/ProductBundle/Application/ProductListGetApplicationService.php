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
use App\Bundle\ProductBundle\Domain\Model\IFeatureImagePathRepository;
use App\Bundle\ProductBundle\Domain\Model\IMeasureUnitRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductCriteria;
use App\Bundle\ProductBundle\Infrastructure\FeatureImagePathRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductListGetApplicationService
{
    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @var ICategoryRepository
     */
    private ICategoryRepository $categoryRepository;

    /**
     * @var IFeatureImagePathRepository
     */
    private IFeatureImagePathRepository $featureImagePathRepository;

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
     * @var IMeasureUnitRepository
     */
    private IMeasureUnitRepository $measureUnitRepository;

    /**
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     * @param IFeatureImagePathRepository $featureImagePathRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductInventoryRepository $productInventoryRepository
     * @param IProductAttributeRepository $productAttributeRepository
     * @param IMeasureUnitRepository $measureUnitRepository
     */
    public function __construct(
        IProductRepository $productRepository,
        ICategoryRepository $categoryRepository,
        IFeatureImagePathRepository $featureImagePathRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductInventoryRepository $productInventoryRepository,
        IProductAttributeRepository $productAttributeRepository,
        IMeasureUnitRepository $measureUnitRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->featureImagePathRepository = $featureImagePathRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productInventoryRepository = $productInventoryRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->measureUnitRepository = $measureUnitRepository;
    }

    /**
     * @param ProductListGetCommand $command
     * @return ProductListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductListGetCommand $command): ProductListGetResult
    {
        $productAttributeValueIds = [];
        foreach ($command->productAttributeValueIds as $productAttributeValueId) {
            $productAttributeValueIds[] = new ProductAttributeValueId($productAttributeValueId);
        }
        $productCriteria = new ProductCriteria(
            $productAttributeValueIds,
        );
        [$products, $pagination] = $this->productRepository->findAll($productCriteria);

        $productResults = [];
        foreach ($products as $product) {
            $category = $this->categoryRepository->findById($product->getCategoryId());
//            $featureImagePath = $this->featureImagePathRepository->findByProductId($product->getProductId());
            $productAttributeValues = $this->productAttributeValueRepository->findByProductId($product->getProductId());

            $productAttributeValueResults = [];
            foreach ($productAttributeValues as $productAttributeValue) {
                $productAttributePrice = $this->productAttributePriceRepository->findByProductAttributeValueId($productAttributeValue->getProductAttributeValueId());
                $productInventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributePrice->getProductAttributeValueId());
                $productAttribute = $this->productAttributeRepository->findById($productAttributeValue->getProductAttributeId());
//                $measureUnit = $this->measureUnitRepository->findById($productAttributeValue->getMeasureUnitId());

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
                    $productAttributePrice->getProductAttributePriceId()
                );
            }

            $productResults[] = new ProductResult(
                $product->getProductId()->asString(),
                $product->getName(),
                $product->getCode(),
                $product->getDescription(),
                $product->getCategoryId()->__toString(),
                $category->getName(),
                $productAttributeValueResults
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new ProductListGetResult(
            $productResults,
            $paginationResult
        );
    }
}
