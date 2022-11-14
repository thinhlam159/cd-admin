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
     * @var string
     */
    private string $value;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var string|null
     */
    private ?string $productAttributeName;

    /**
     * @var string|null
     */
    private ?string $measureUnitName;

    /**
     * @param ProductAttributeValueId $productAttributeValueId
     * @param ProductId $productId
     * @param ProductAttributeId $productAttributeId
     * @param string $value
     * @param string $code
     * @param string|null $productAttributeName
     * @param string|null $measureUnitName
     */
    public function __construct(ProductAttributeValueId $productAttributeValueId, ProductId $productId, ProductAttributeId $productAttributeId, string $value, string $code, ?string $productAttributeName, ?string $measureUnitName)
    {
        $this->productAttributeValueId = $productAttributeValueId;
        $this->productId = $productId;
        $this->productAttributeId = $productAttributeId;
        $this->value = $value;
        $this->code = $code;
        $this->productAttributeName = $productAttributeName;
        $this->measureUnitName = $measureUnitName;
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getProductAttributeName(): ?string
    {
        return $this->productAttributeName;
    }

    /**
     * @return string|null
     */
    public function getMeasureUnitName(): ?string
    {
        return $this->measureUnitName;
    }
}
