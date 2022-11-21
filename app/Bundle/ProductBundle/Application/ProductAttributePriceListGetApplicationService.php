<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\NoticePriceType;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $productAttributePriceResults[] = new ProductAttributePriceResult(
                $result->getProductAttributePriceId()->asString(),
                $result->getProductAttributeValueId()->asString(),
                $result->getPrice(),
                $result->getMonetaryUnitType()->getValue(),
                $result->getNoticePriceType()->getValue(),
                $result->isCurrent(),
            );
        }

        return new ProductAttributePriceListGetResult($productAttributePriceResults);
    }
}
