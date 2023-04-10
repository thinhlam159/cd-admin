<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductInventoryExportGood
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
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductInventoryUpdateType
     */
    private ProductInventoryUpdateType $productInventoryUpdateType;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ExportGoodProductId
     */
    private ExportGoodProductId $exportGoodProductId;

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
     * @param ProductInventoryUpdateType $productInventoryUpdateType
     * @param ExportGoodProductId $exportGoodProductId
     * @param int $numberOfUpdate
     * @param bool $isCurrent
     */
    public function __construct(ProductInventoryId $productInventoryId, ProductAttributeValueId $productAttributeValueId, int $count, ProductInventoryUpdateType $productInventoryUpdateType, ExportGoodProductId $exportGoodProductId, int $numberOfUpdate, bool $isCurrent)
    {
        $this->productInventoryId = $productInventoryId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->count = $count;
        $this->productInventoryUpdateType = $productInventoryUpdateType;
        $this->exportGoodProductId = $exportGoodProductId;
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
     * @return ProductInventoryUpdateType
     */
    public function getProductInventoryUpdateType(): ProductInventoryUpdateType
    {
        return $this->productInventoryUpdateType;
    }

    /**
     * @return ExportGoodProductId
     */
    public function getExportGoodProductId(): ExportGoodProductId
    {
        return $this->exportGoodProductId;
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
