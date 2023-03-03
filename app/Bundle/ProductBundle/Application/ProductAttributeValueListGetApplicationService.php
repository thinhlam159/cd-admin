<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueCriteria;

class ProductAttributeValueListGetApplicationService
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
     * @param ProductAttributeValueListGetCommand $command
     * @return ProductAttributeValueListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductAttributeValueListGetCommand $command): ProductAttributeValueListGetResult
    {
        $productAttributeValueCriteria = new ProductAttributeValueCriteria(
            $command->productId ?? null
        );
        $productAttributeValues = $this->productAttributeValueRepository->findAll($productAttributeValueCriteria);
        $productAttributeValueResults = [];
        foreach ($productAttributeValues as $productAttributeValue) {
            $productAttribute = $this->productAttributeRepository->findById($productAttributeValue->getProductAttributeId());
            $inventory = $this->productInventoryRepository->findByProductAttributeValueId($productAttributeValue->getProductAttributeValueId());
            $price = $this->productAttributePriceRepository->findByProductAttributeValueId($productAttributeValue->getProductAttributeValueId());
            $productAttributeValueResults[] = new ProductAttributeValueResult(
                $productAttributeValue->getProductAttributeValueId()->asString(),
                $productAttributeValue->getProductId()->asString(),
                $productAttribute->getName(),
                $productAttributeValue->getValue(),
                $productAttributeValue->getCode(),
                $productAttributeValue->getMeasureUnitType()->getValue(),
                $inventory->getCount(),
                $price->getPrice(),
                $price->getMonetaryUnitType()->getValue(),
                $price->getNoticePriceType()->getValue()
            );
        }

        return new ProductAttributeValueListGetResult($productAttributeValueResults);
    }
}
