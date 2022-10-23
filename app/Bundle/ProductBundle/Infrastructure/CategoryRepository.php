<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use App\Models\Categories as ModelCategory;

class CategoryRepository implements ICategoryRepository
{
    /**
     * @inheritDoc
     */
    public function create(Category $category): CategoryId
    {
        $result = ModelCategory::create([
            'id' => $category->getCategoryId()->asString(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'parent_id' => $category->getParentId()->asString(),
    	]);

        return new CategoryId($result->id);
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelCategory::all();
        $categories = [];

        foreach ($entities as $entity) {
            $categories[] = new Category(
                new CategoryId($entity['id']),
                $entity['name'],
                $entity['slug'],
                new CategoryId($entity['parent_id']),
            );
        }

        return $categories;
    }
}
