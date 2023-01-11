<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryPostApplicationService
{
    private $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(CategoryPostCommand $command): CategoryPostResult
    {
//        $existingEmail = $this->categoryRepository->checkExistingEmail($command->email);
//        if ($existingEmail) {
//            throw new InvalidArgumentException('Existing Email!');
//        }
        $categoryId = CategoryId::newId();
        $category = new Category(
            $categoryId,
            $command->name,
            $command->slug,
            !is_null($command->parentId) ? new CategoryId($command->parentId) : CategoryId::newId(),
        );

        DB::beginTransaction();
        try {
            $categoryId = $this->categoryRepository->create($category);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add category fail!');
        }

        return new CategoryPostResult($categoryId->__toString());
    }
}
