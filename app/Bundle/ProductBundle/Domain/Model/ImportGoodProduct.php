<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class ImportGoodProduct
{

    /**
     * @var ImportGoodProductId
     */
    private ImportGoodProductId $goodProductId;

    /**
     * @var ImportGoodId
     */
    private ImportGoodId $importGoodId;

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
    private int $price;

    /**
     * @var MonetaryUnitType
     */
    private MonetaryUnitType $monetaryUnitType;

    /**
     * @var int
     */
    private int $count;

    /**
     * @var MeasureUnitType
     */
    private MeasureUnitType $measureUnitType;

    /**
     * @param ImportGoodProductId $goodProductId
     * @param ImportGoodId $importGoodId
     * @param ProductId $productId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param int $price
     * @param MonetaryUnitType $monetaryUnitType
     * @param int $count
     * @param MeasureUnitType $measureUnitType
     */
    public function __construct(ImportGoodProductId $goodProductId, ImportGoodId $importGoodId, ProductId $productId, ProductAttributeValueId $productAttributeValueId, int $price, MonetaryUnitType $monetaryUnitType, int $count, MeasureUnitType $measureUnitType)
    {
        $this->goodProductId = $goodProductId;
        $this->importGoodId = $importGoodId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->count = $count;
        $this->measureUnitType = $measureUnitType;
    }

    /**
     * @return ImportGoodProductId
     */
    public function getGoodProductId(): ImportGoodProductId
    {
        return $this->goodProductId;
    }

    /**
     * @return ImportGoodId
     */
    public function getImportGoodId(): ImportGoodId
    {
        return $this->importGoodId;
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
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return MonetaryUnitType
     */
    public function getMonetaryUnitType(): MonetaryUnitType
    {
        return $this->monetaryUnitType;
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
