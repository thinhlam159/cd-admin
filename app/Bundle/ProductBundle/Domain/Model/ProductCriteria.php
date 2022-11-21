<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductCriteria
{
    /**
     * @var ProductAttributeValueId[]
     */
    private array $productAttributeValueIds;

    /**
     * @param ProductAttributeValueId[] $productAttributeValueIds
     */
    public function __construct(array $productAttributeValueIds)
    {
        $this->productAttributeValueIds = $productAttributeValueIds;
    }

    /**
     * @return array
     */
    public function getProductAttributeValueIds(): array
    {
        return $this->productAttributeValueIds;
    }
}
