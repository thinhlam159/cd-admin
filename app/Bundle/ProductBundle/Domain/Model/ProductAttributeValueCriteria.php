<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductAttributeValueCriteria
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductId|null
     */
    private ?ProductId $productId;

    /**
     * @param ProductId|null $productId
     */
    public function __construct(?ProductId $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return ProductId|null
     */
    public function getProductId(): ?ProductId
    {
        return $this->productId;
    }
}
