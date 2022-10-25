<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductPutApplicationService
{
    /**
     * @var IProductRepository
     */
    private $productRepository;

    /**
     * @param IProductRepository $productRepository
     */
    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductPutCommand $command
     * @return ProductPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductPutCommand $command): ProductPutResult
    {
        $productId = new ProductId($command->productId);
        $existingName = $this->productRepository->checkExistingName($command->name, $productId);
        if ($existingName) {
            throw new InvalidArgumentException('Existing Email!');
        }

        $product = new Product(
            $productId,
            $command->name,
            $command->price,
            $command->featureImagePath,
            $command->price,
            new UserId($command->userId),
            new CategoryId($command->categoryId),
        );

        DB::beginTransaction();
        try {
            $productId = $this->productRepository->update($product);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Update product fail!');
        }

        return new ProductPutResult($productId->__toString());
    }
}
