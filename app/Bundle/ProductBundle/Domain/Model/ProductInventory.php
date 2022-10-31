<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductInventory
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductInventoryId
     */
    private ProductInventoryId $productInventoryId;

    /**
     * @var ProductAttributeValueId
     */
    private ProductAttributeValueId $productAttributeValueId;

    /**
     * @var int
     */
    private int $count;

    /**
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @param ProductInventoryId $productInventoryId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param int $count
     * @param bool $isCurrent
     */
    public function __construct(ProductInventoryId $productInventoryId, ProductAttributeValueId $productAttributeValueId, int $count, bool $isCurrent)
    {
        $this->productInventoryId = $productInventoryId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->count = $count;
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
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }
}
