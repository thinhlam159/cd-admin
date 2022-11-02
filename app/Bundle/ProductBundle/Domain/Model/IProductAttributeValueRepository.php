<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributeValueRepository
{
    /**
     * @param ProductAttributeValue $productAttribute
     * @return ProductAttributeValueId
     */
    public function create(ProductAttributeValue $productAttribute): ProductAttributeValueId;

    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @return ProductAttributeValue|null
     */
    public function findById(ProductAttributeValueId $productAttributeValueId): ?ProductAttributeValue;

    /**
     * @param ProductId $productId
     * @return ProductAttributeValue[]
     */
    public function findByProductId(ProductId $productId): array;

    /**
     * @param ProductAttributeValue $productAttributeValue
     * @return ProductAttributeValueId
     */
    public function update(ProductAttributeValue $productAttributeValue): ProductAttributeValueId;
}
