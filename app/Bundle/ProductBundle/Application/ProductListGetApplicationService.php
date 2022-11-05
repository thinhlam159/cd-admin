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
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
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
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     * @param IFeatureImagePathRepository $featureImagePathRepository
     */
    public function __construct(IProductRepository $productRepository, ICategoryRepository $categoryRepository, IFeatureImagePathRepository $featureImagePathRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->featureImagePathRepository = $featureImagePathRepository;
    }

    /**
     * @param ProductListGetCommand $command
     * @return ProductListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductListGetCommand $command): ProductListGetResult
    {
        [$products, $pagination] = $this->productRepository->findAll();

        $productResults = [];
        foreach ($products as $product) {
            $category = $this->categoryRepository->findById($product->getCategoryId());
            $featureImagePath = $this->featureImagePathRepository->findByProductId($product->getProductId());
//            $productAttributeValues = $this->productAttributeValueRepository->findByProductId($product->getProductId());

//            $productAttributeValueResults = [];
//            foreach ($productAttributeValues as $productAttributeValue) {
//                $productAttributePrice = $this->productAttributePriceRepository->findByAttributeValueId($productAttributeValue->getProductAttributeValueId());
//                $productInventory = $this->productInventoryRepository->findByProductId($productAttributePrice->getProductAttributeValueId());
//                $productAttributeValueResults[] = new ProductAttributeValueResult(
//                    $productAttributeValue->getProductAttributeValueId()->asString(),
//                    $productAttributeValue->getProductId()->asString(),
//                    $productAttributeValue->getProductAttributeName(),
//                    $productAttributeValue->getValue(),
//                    $productAttributeValue->getNameByAttribute(),
//                    $productAttributeValue->getMeasureUnitName(),
//                    $productInventory->getCount(),
//                    $productAttributePrice->getPrice(),
//                    $productAttributePrice->getMonetaryUnitType()->getValue(),
//                );
//            }
            $productResults[] = new ProductResult(
                $product->getProductId()->asString(),
                $product->getName(),
                $product->getCode(),
                $product->getDescription(),
                $product->getCategoryId()->__toString(),
                $category->getName(),
                $featureImagePath->getPath()
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
