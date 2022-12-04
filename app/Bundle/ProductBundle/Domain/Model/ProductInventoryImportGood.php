<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductInventoryImportGood
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductInventoryId
     */
    private ProductInventoryId $productInventoryId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId
     */
    private ProductAttributeValueId $productAttributeValueId;

    /**
     * @var int
     */
    private int $count;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\MeasureUnitType
     */
    private MeasureUnitType $measureUnitType;

    /**
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @param ProductInventoryId $productInventoryId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param MeasureUnitType $measureUnitType
     * @param int $count
     * @param bool $isCurrent
     */
    public function __construct(
        ProductInventoryId $productInventoryId,
        ProductAttributeValueId $productAttributeValueId,
        int $count,
        MeasureUnitType $measureUnitType,
        bool $isCurrent
    )
    {
        $this->productInventoryId = $productInventoryId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
        $this->isCurrent = $isCurrent;
    }

    /**
     * @return ProductInventoryId
     */
    public function getProductInventoryId(): ProductInventoryId
    {
        return $this->productInventoryId;
    }

    /**
     * @return ProductAttributeValueId
     */
    public function getProductAttributeValueId(): ProductAttributeValueId
    {
        return $this->productAttributeValueId;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return MeasureUnitType
     */
    public function getMeasureUnitType(): MeasureUnitType
    {
        return $this->measureUnitType;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }
}
