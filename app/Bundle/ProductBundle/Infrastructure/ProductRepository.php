<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Common\Constants\PaginationConst;
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
            'code' => $product->getCode(),
            'description' => $product->getDescription(),
            'category_id' => $product->getCategoryId()->asString(),
    	]);
        if (!$result) {
            throw new \Exception();
        }

        return new ProductId($product->getProductId());
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelProduct::paginate(PaginationConst::PAGINATE_ROW);
        $products = [];

        foreach ($entities as $entity) {
            $products[] = new Product(
                new ProductId($entity['id']),
                $entity['name'],
                $entity['code'],
                $entity['description'],
                new CategoryId($entity['category_id']),
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
    public function findById(ProductId $productId): ?Product
    {
        $entity = ModelProduct::find($productId->asString());

        if (!$entity) {
            return null;
        }

        return new Product(
            new ProductId($entity['id']),
            $entity['name'],
            $entity['code'],
            $entity['description'],
            new CategoryId($entity['category_id']),
        );
    }

    /**
     * @inheritDoc
     */
    public function update(Product $product): ProductId
    {
        $productId = $product->getProductId();
        $entity = ModelProduct::find($productId->asString());

        $data = [
            'id' => $product->getProductId()->asString(),
            'name' => $product->getName(),
            'code' => $product->getCode(),
            'description' => $product->getDescription(),
            'category_id' => $product->getCategoryId()->asString(),
        ];
        $result = $entity->update($data);
        if (!$result) {
            throw new Exception();
        }

        return $productId;
    }
}
