<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributeValueRepository
{
    /**
     * @param ProductAttributeValue $productAttributeValue
     * @return ProductAttributeValueId|null
     */
    public function create(ProductAttributeValue $productAttributeValue): ?ProductAttributeValueId;

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
     * @return ProductAttributeValueId|null
     */
    public function update(ProductAttributeValue $productAttributeValue): ?ProductAttributeValueId;

    /**
     * @param \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueCriteria $productAttributeValueCriteria
     * @return ProductAttributeValue[]
     */
    public function findAll(ProductAttributeValueCriteria $productAttributeValueCriteria): array;
}
