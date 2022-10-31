<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductAttributeValue
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId
     */
    private ProductAttributeValueId $productAttributeValueId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductId
     */
    private ProductId $productId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeId
     */
    private ProductAttributeId $productAttributeId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\MeasureUnitId
     */
    private MeasureUnitId $measureUnitId;

    /**
     * @var string
     */
    private string $value;

    /**
     * @var string
     */
    private string $nameByAttribute;

    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @param ProductId $productId
     * @param ProductAttributeId $productAttributeId
     * @param MeasureUnitId $measureUnitId
     * @param string $value
     * @param string $nameByAttribute
     */
    public function __construct(ProductAttributeValueId $productAttributeValueId, ProductId $productId, ProductAttributeId $productAttributeId, MeasureUnitId $measureUnitId, string $value, string $nameByAttribute)
    {
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productId = $productId;
        $this->productAttributeId = $productAttributeId;
        $this->measureUnitId = $measureUnitId;
        $this->value = $value;
        $this->nameByAttribute = $nameByAttribute;
    }

    /**
     * @return ProductAttributeValueId
     */
    public function getProductAttributeValueId(): ProductAttributeValueId
    {
        return $this->productAttributeValueId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return ProductAttributeId
     */
    public function getProductAttributeId(): ProductAttributeId
    {
        return $this->productAttributeId;
    }

    /**
     * @return MeasureUnitId
     */
    public function getMeasureUnitId(): MeasureUnitId
    {
        return $this->measureUnitId;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getNameByAttribute(): string
    {
        return $this->nameByAttribute;
    }
}
