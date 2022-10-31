<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductAttribute
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeId
     */
    private ProductAttributeId $productAttributeId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param \App\Bundle\ProductBundle\Domain\Model\ProductAttributeId $productAttributeId productAttributeId
     * @param string $name name
     */
    public function __construct(ProductAttributeId $productAttributeId, string $name)
    {
        $this->productAttributeId = $productAttributeId;
        $this->name = $name;
    }

    /**
     * @return \App\Bundle\ProductBundle\Domain\Model\ProductAttributeId
     */
    public function getProductAttributeId(): ProductAttributeId
    {
        return $this->productAttributeId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
