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
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType
     */
    private ProductInventoryUpdateType $productInventoryUpdateType;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ImportGoodProductId
     */
    private ImportGoodProductId $importGoodProductId;

    /**
     * @var int
     */
    private int $numberOfUpdate;

    /**
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @param ProductInventoryId $productInventoryId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param int $count
     * @param MeasureUnitType $measureUnitType
     * @param ProductInventoryUpdateType $productInventoryUpdateType
     * @param ImportGoodProductId $importGoodProductId
     * @param int $numberOfUpdate
     * @param bool $isCurrent
     */
    public function __construct(ProductInventoryId $productInventoryId, ProductAttributeValueId $productAttributeValueId, int $count, MeasureUnitType $measureUnitType, ProductInventoryUpdateType $productInventoryUpdateType, ImportGoodProductId $importGoodProductId, int $numberOfUpdate, bool $isCurrent)
    {
        $this->productInventoryId = $productInventoryId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
        $this->productInventoryUpdateType = $productInventoryUpdateType;
        $this->importGoodProductId = $importGoodProductId;
        $this->numberOfUpdate = $numberOfUpdate;
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
     * @return ProductInventoryUpdateType
     */
    public function getProductInventoryUpdateType(): ProductInventoryUpdateType
    {
        return $this->productInventoryUpdateType;
    }

    /**
     * @return ImportGoodProductId
     */
    public function getImportGoodProductId(): ImportGoodProductId
    {
        return $this->importGoodProductId;
    }

    /**
     * @return int
     */
    public function getNumberOfUpdate(): int
    {
        return $this->numberOfUpdate;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }
}
