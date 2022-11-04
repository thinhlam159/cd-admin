<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributePriceRepository
{

    /**
     * @param ProductAttributePrice $productAttributePrice
     * @return ProductAttributePriceId
     */
    public function create(ProductAttributePrice $productAttributePrice): ProductAttributePriceId;

    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @return ProductAttributePrice|null
     */
    public function findByAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductAttributePrice;

    /**
     * @param ProductAttributePrice $productAttributePrice
     * @return ProductAttributePriceId
     */
    public function update(ProductAttributePrice $productAttributePrice): ProductAttributePriceId;
}
