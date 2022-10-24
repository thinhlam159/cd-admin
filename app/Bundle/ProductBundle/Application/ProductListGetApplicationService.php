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
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductListGetApplicationService
{
    private $productRepository;

    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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
        $categoryResults = [];
        foreach ($products as $product) {
            $categoryResults[] = new ProductResult(
                $product->getProductId()->asString(),
                $product->getName(),
                $product->getPrice(),
                $product->getFeatureImagePath(),
                $product->getContent(),
                $product->getUserId()->__toString(),
                $product->getCategoryId()->__toString(),
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new ProductListGetResult(
            $categoryResults,
            $paginationResult
        );
    }
}
