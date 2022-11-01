<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductAttribute;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValue;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\ProductAttributeValue as ModelProductAttributeValue;
use PHPUnit\Framework\Exception;

class ProductAttributeValueRepository implements IProductAttributeValueRepository
{
    /**
     * @inheritDoc
     */
    public function create(ProductAttributeValue $productAttributeValue): ProductAttributeValueId
    {
        $result = ModelProductAttributeValue::create([
            'id' => $productAttributeValue->getProductAttributeValueId()->asString(),
            'product_id' => $productAttributeValue->getProductId()->asString(),
            'product_attribute_id' => $productAttributeValue->getProductAttributeId()->asString(),
            'value' => $productAttributeValue->getParentId()->asString(),
            'name_by_attribute' => $productAttributeValue->getParentId()->asString(),
        ]);

        return new CategoryId($result->id);
    }
}
