<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\Common\Infrastructure\Transaction;
use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryGetApplicationService
{
    /**
     * @var ICategoryRepository
     */
    private $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryPutCommand $command
     * @return CategoryPutResult
     * @throws InvalidArgumentException
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
            new CategoryId($command->name)
        );

        DB::beginTransaction();
        try {
            $categoryId = $this->categoryRepository->update($category);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add category fail!');
        }

        return new CategoryPutResult(
            $categoryId->__toString(),
        );
    }
}
