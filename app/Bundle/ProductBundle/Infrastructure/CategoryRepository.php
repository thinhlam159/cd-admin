<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\Category;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\ICategoryRepository;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\Categories as ModelCategory;
use PHPUnit\Framework\Exception;

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
        $entities = ModelCategory::paginate(PaginationConst::PAGINATE_ROW);
        $categories = [];
        foreach ($entities as $entity) {
            $categories[] = new Category(
                new CategoryId($entity['id']),
                $entity['name'],
                $entity['slug'],
                new CategoryId($entity['parent_id']),
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );


        return [$categories, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findById(CategoryId $categoryId): ?Category
    {
        $entity = ModelCategory::find($categoryId->asString());

        if (!$entity) {
            return null;
        }

        return new Category(
            new CategoryId($entity['id']),
            $entity['name'],
            $entity['slug'],
            $entity['parent_id'],
        );
    }

    /**
     * @inheritDoc
     */
    public function update(Category $category): CategoryId
    {
        $categoryId = $category->getCategoryId();
        $entity = ModelCategory::find($categoryId->asString());

        $data = [
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'parent_id' => $category->getParentId()->asString(),
        ];
        $result = $entity->update($data);
        if (!$result) {
            throw new Exception();
        }

        return $categoryId;
    }
}
