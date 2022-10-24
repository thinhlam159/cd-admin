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

class ProductPostApplicationService
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
     * @param ProductPostCommand $command
     * @return ProductPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ProductPostCommand $command): ProductPostResult
    {
//        $existingEmail = $this->categoryRepository->checkExistingEmail($command->email);
//        if ($existingEmail) {
//            throw new InvalidArgumentException('Existing Email!');
//        }
        $productId = ProductId::newId();
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
            $productId = $this->productRepository->create($product);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new ProductPostResult($productId->__toString());
    }
}
