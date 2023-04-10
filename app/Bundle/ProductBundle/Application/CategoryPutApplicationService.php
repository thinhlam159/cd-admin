<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryPutApplicationService
{
    private $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryPutCommand $command
     * @return CategoryPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(CategoryPutCommand $command): CategoryPutResult
    {
        $categoryId = new CategoryId($command->categoryId);
        $category = $this->categoryRepository->findById($categoryId);
        if (!$category) {
            throw new InvalidArgumentException('record not found!');
        }
        $category = new Category(
            $categoryId,
            $command->name,
            $command->slug,
            !empty($command->parentId) ? new CategoryId($command->parentId) : $categoryId
        );

        DB::beginTransaction();
        try {
            $categoryId = $this->categoryRepository->update($category);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new CategoryPutResult($categoryId->asString());
    }
}
