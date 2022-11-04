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
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductAttributeListGetApplicationService
{
    /**
     * @var IProductAttributeRepository
     */
    private IProductAttributeRepository $productAttributeRepository;

    /**
     * @param IProductAttributeRepository $productAttributeRepository
     */
    public function __construct(IProductAttributeRepository $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

    /**
     * @param ProductAttributeListGetCommand $command
     * @return ProductAttributeListGetResult
     */
    public function handle(ProductAttributeListGetCommand $command): ProductAttributeListGetResult
    {
        $productAttributes = $this->productAttributeRepository->findAll();

        $productAttributeResults = [];
        foreach ($productAttributes as $productAttribute) {
            $productAttributeResults = new ProductAttributeResult(
                $productAttribute->getProductAttributeId()->asString(),
                $productAttribute->getName(),
            );
        }

        return new ProductAttributeListGetResult(
            $productAttributeResults
        );
    }
}
