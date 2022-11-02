<?php
namespace App\Bundle\ProductBundle\Infrastructure;

use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\ProductBundle\Domain\Model\CategoryId;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributePriceRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\Product;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePrice;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\ProductAttributePrice as ModelProductAttributePrice;
use PHPUnit\Framework\Exception;

class ProductAttributePriceRepository implements IProductAttributePriceRepository
{
    /**
     * @inheritDoc
     */
    public function create(ProductAttributePrice $productAttributePrice): ProductAttributePriceId
    {
        $result = ModelProductAttributePrice::create([
            'id' => $productAttributePrice->getProductAttributePriceId()->asString(),
            'product_attribute_value_id' => $productAttributePrice->getProductAttributeValueId()->asString(),
            'price' => $productAttributePrice->getPrice(),
            'monetary_unit' => $productAttributePrice->getMonetaryUnitType()->getValue(),
            'is_current' => $productAttributePrice->isCurrent(),
        ]);

        if(!$result) {
            throw new \Exception();
        }

        return $productAttributePrice->getProductAttributePriceId();
    }

    /**
     * @inheritDoc
     */
    public function findByAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductAttributePrice
    {
        $result = ModelProductAttributePrice::latest()->first()('product_attribute_value_id', $productAttributeValueId->asString());
        if (!$result) {
            return null;
        }

        return new ProductAttributePrice(
            new ProductAttributePriceId($result['id']),
            new ProductAttributeValueId($result['product_attribute_value_id']),
            $result['price'],
            MonetaryUnitType::fromType($result['monetary_unit']),
            $result['is_current']
        );
    }

}
