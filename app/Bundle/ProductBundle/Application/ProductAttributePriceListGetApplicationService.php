<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;

class ProductAttributePriceListGetApplicationService
{
    /**
     * @var IProductAttributePriceRepository
     */
    private IProductAttributePriceRepository $productAttributePriceRepository;

    /**
     * @param IProductAttributePriceRepository $productAttributePriceRepository
     */
    public function __construct(IProductAttributePriceRepository $productAttributePriceRepository)
    {
        $this->productAttributePriceRepository = $productAttributePriceRepository;
    }

    /**
     * @param ProductAttributePriceListGetCommand $command
     * @return ProductAttributePriceListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductAttributePriceListGetCommand $command): ProductAttributePriceListGetResult
    {
        $results = $this->productAttributePriceRepository->findAll();
        $productAttributePriceResults = [];
        foreach ($results as $result) {
            $standardPrice = $result->getPrice() / $result->getNoticePriceType()->getAmountValue();
            $standardPrice = floor($standardPrice);
            $productAttributePriceResults[] = new ProductAttributePriceResult(
                $result->getProductAttributePriceId()->asString(),
                $result->getProductAttributeValueId()->asString(),
                $result->getPrice(),
                $result->getMonetaryUnitType()->getValue(),
                $result->getNoticePriceType()->getValue(),
                $result->isCurrent(),
                $standardPrice
            );
        }

        return new ProductAttributePriceListGetResult($productAttributePriceResults);
    }
}
