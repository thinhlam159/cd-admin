<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;

class CategoryGetApplicationService
{
    /**
     * @var ICategoryRepository
     */
    private ICategoryRepository $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryGetCommand $command
     * @return CategoryGetResult
     * @throws InvalidArgumentException
     */
    public function handle(CategoryGetCommand $command): CategoryGetResult
    {
        $categoryId = new CategoryId($command->categoryId);
        $category = $this->categoryRepository->findById($categoryId);
        if (!$category) {
            throw new InvalidArgumentException('record not found!');
        }

        return new CategoryGetResult(
            $category->getCategoryId()->asString(),
            $category->getName(),
            $category->getSlug(),
            $category->getParentId()->asString()
        );
    }
}
