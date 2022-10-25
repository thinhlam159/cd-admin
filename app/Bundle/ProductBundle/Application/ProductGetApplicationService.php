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
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductGetApplicationService
{
    private $productRepository;

    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductGetCommand $command
     * @return ProductGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductGetCommand $command): ProductGetResult
    {
        $product = $this->productRepository->findById(new ProductId($command->productId));
        if (!$product) {
            throw new InvalidArgumentException('Record not found!');
        }

        return new ProductGetResult(
            $product->getProductId()->asString(),
            $product->getName(),
            $product->getPrice(),
            $product->getFeatureImagePath(),
            $product->getContent(),
            $product->getUserId()->__toString(),
            $product->getCategoryId()->__toString(),
        );
    }
}
