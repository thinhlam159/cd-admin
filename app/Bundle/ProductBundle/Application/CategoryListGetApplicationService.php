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
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryListGetApplicationService
{
    private $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryListGetCommand $command
     * @return CategoryListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(CategoryListGetCommand $command): CategoryListGetResult
    {
        [$categories, $pagination] = $this->categoryRepository->findAll();
        $categoryResults = [];
        foreach ($categories as $category) {
            $categoryResults[] = new CategoryResult(
                $category->getCategoryId()->__toString(),
                $category->getName(),
                $category->getSlug(),
                $category->getParentId()->asString(),
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new CategoryListGetResult(
            $categoryResults,
            $paginationResult
        );
    }
}
