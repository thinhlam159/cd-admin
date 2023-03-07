<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;

class ProductPriceAllListGetApplicationService
{
    /**
     * @var IProductAttributePriceRepository
     */
    private IProductAttributePriceRepository $productAttributePriceRepository;

    /**
     * @var IProductAttributeValueRepository
     */
    private IProductAttributeValueRepository $productAttributeValueRepository;

    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     * @param IProductAttributeValueRepository $productAttributeValueRepository
     * @param IProductRepository $productRepository
     */
    public function __construct(
        IProductAttributePriceRepository $productAttributePriceRepository,
        IProductAttributeValueRepository $productAttributeValueRepository,
        IProductRepository $productRepository
    )
    {
        $this->productAttributePriceRepository = $productAttributePriceRepository;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductPriceAllListGetCommand $command
     * @return ProductPriceAllListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductPriceAllListGetCommand $command): ProductPriceAllListGetResult
    {
        $originalAttributeValues = $this->productAttributeValueRepository->findAllOriginal();
        $productAttributePriceResults = [];
        foreach ($originalAttributeValues as $productAttributeValue) {
            $productAttributePrice = $this->productAttributePriceRepository->findByProductAttributeValueId($productAttributeValue->getProductAttributeValueId());
            $product = $this->productRepository->findById($productAttributeValue->getProductId());
            $productAttributePriceResults[] = new ProductPriceResult(
                $productAttributeValue->getProductId(),
                $product->getName(),
                $product->getCode(),
                $productAttributePrice->getProductAttributePriceId()->asString(),
                $productAttributePrice->getProductAttributeValueId()->asString(),
                $productAttributePrice->getPrice(),
                $productAttributePrice->getMonetaryUnitType()->getValue(),
                $productAttributePrice->getNoticePriceType()->getValue(),
                $productAttributePrice->isCurrent(),
            );
        }

        return new ProductPriceAllListGetResult($productAttributePriceResults);
    }
}
