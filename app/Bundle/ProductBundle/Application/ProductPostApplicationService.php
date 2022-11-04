<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\FeatureImagePath;
use App\Bundle\ProductBundle\Domain\Model\FeatureImagePathId;
use App\Bundle\ProductBundle\Domain\Model\IFeatureImagePathRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductPostApplicationService
{
    /**
     * @var IProductRepository
     */
    private IProductRepository $productRepository;

    /**
     * @var IFeatureImagePathRepository
     */
    private IFeatureImagePathRepository $featureImagePathRepository;

    /**
     * @param IProductRepository $productRepository
     * @param IFeatureImagePathRepository $featureImagePathRepository
     */
    public function __construct(IProductRepository $productRepository, IFeatureImagePathRepository $featureImagePathRepository)
    {
        $this->productRepository = $productRepository;
        $this->featureImagePathRepository = $featureImagePathRepository;
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
        $categoryId = new CategoryId($command->categoryId);

        $product = new Product(
            $productId,
            $command->name,
            $command->code,
            $command->description,
            $categoryId,
        );

        $featureImagePathId = FeatureImagePathId::newId();
        $featureImagePath = new FeatureImagePath(
            $featureImagePathId,
            $productId,
            null,
            true,
            $command->path
        );

        DB::beginTransaction();
        try {
            $productId = $this->productRepository->create($product);
            $this->featureImagePathRepository->create($featureImagePath);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add product fail!');
        }

        return new ProductPostResult($productId->__toString());
    }
}
