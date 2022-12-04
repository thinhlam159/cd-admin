<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributePriceRepository
{
    /**
     * @param ProductAttributePrice $productAttributePrice
     * @return ProductAttributePriceId|null
     */
    public function create(ProductAttributePrice $productAttributePrice): ?ProductAttributePriceId;

    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @return ProductAttributePrice|null
     */
    public function findByProductAttributeValueId(ProductAttributeValueId $productAttributeValueId): ?ProductAttributePrice;

    /**
     * @param ProductAttributePrice $productAttributePrice
     * @return ProductAttributePriceId
     */
    public function update(ProductAttributePrice $productAttributePrice): ProductAttributePriceId;

    /**
     * @param ProductAttributePrice[] $productAttributePrices
     * @return ProductAttributePriceId[]
     */
    public function updateMany(array $productAttributePrices): array;

    /**
     * @param ProductAttributePrice[] $productAttributePrices
     * @return ProductAttributePriceId[]
     */
    public function createMany(array $productAttributePrices): array;

    /**
     * @return ProductAttributePrice[]
     */
    public function findAll(): array;

    /**
     * @param ProductAttributePriceId $productAttributePriceId
     * @return ProductAttributePrice|null
     */
    public function findById(ProductAttributePriceId $productAttributePriceId): ?ProductAttributePrice;
}
