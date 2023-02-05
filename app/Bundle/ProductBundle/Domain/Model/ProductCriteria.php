<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductCriteria
{
    /**
     * @var ProductAttributeValueId[]
     */
    private array $productAttributeValueIds;

    /**
     * @var string|null
     */
    private ?string $keyword;

    /**
     * @param ProductAttributeValueId[] $productAttributeValueIds
     * @param string|null $keyword
     */
    public function __construct(array $productAttributeValueIds, ?string $keyword)
    {
        $this->productAttributeValueIds = $productAttributeValueIds;
        $this->keyword = $keyword;
    }

    /**
     * @return array
     */
    public function getProductAttributeValueIds(): array
    {
        return $this->productAttributeValueIds;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }
}
