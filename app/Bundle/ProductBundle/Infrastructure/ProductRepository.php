<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\Product as ModelProduct;
use PHPUnit\Framework\Exception;

class ProductRepository implements IProductRepository
{
    /**
     * @inheritDoc
     */
    public function create(Product $product): ProductId
    {
        $result = ModelProduct::create([
            'id' => $product->getProductId()->asString(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'feature_image_path' => $product->getFeatureImagePath(),
            'user_id' => $product->getUserId()->asString(),
            'category_id' => $product->getCategoryId()->asString(),
    	]);

        return new ProductId($result->id);
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelProduct::all();
        $products = [];

        foreach ($entities as $entity) {
            $products[] = new Product(
                new ProductId($entity['id']),
                $entity['name'],
                $entity['price'],
                $entity['featureImagePath'],
                $entity['content'],
                new UserId($entity['parent_id']),
                new CategoryId($entity['id']),
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );


        return [$products, $pagination];
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
