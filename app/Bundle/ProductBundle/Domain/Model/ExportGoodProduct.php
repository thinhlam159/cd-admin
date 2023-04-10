<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class ExportGoodProduct
{
    /**
     * @var ExportGoodProductId
     */
    private ExportGoodProductId $exportGoodProductId;

    /**
     * @var ExportGoodId
     */
    private ExportGoodId $exportGoodId;

    /**
     * @var ProductId
     */
    private ProductId $productId;

    /**
     * @var ProductAttributeValueId
     */
    private ProductAttributeValueId $productAttributeValueId;

    /**
     * @var int
     */
    private int $count;

    /**
     * @var MeasureUnitType
     */
    private MeasureUnitType $measureUnitType;

    /**
     * @param ExportGoodProductId $exportGoodProductId
     * @param ExportGoodId $exportGoodId
     * @param ProductId $productId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param int $count
     * @param MeasureUnitType $measureUnitType
     */
    public function __construct(ExportGoodProductId $exportGoodProductId, ExportGoodId $exportGoodId, ProductId $productId, ProductAttributeValueId $productAttributeValueId, int $count, MeasureUnitType $measureUnitType)
    {
        $this->exportGoodProductId = $exportGoodProductId;
        $this->exportGoodId = $exportGoodId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
    }

    /**
     * @return ExportGoodProductId
     */
    public function getExportGoodProductId(): ExportGoodProductId
    {
        return $this->exportGoodProductId;
    }

    /**
     * @return ExportGoodId
     */
    public function getExportGoodId(): ExportGoodId
    {
        return $this->exportGoodId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
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
}
