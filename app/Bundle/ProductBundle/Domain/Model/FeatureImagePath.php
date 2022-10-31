<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class FeatureImagePath
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\FeatureImagePathId
     */
    private FeatureImagePathId $featureImagePathId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductId
     */
    private ProductId $productId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId|null
     */
    private ?ProductAttributeValueId $productAttributeValueId;

    /**
     * @var bool
     */
    private bool $isAvatar;

    /**
     * @param FeatureImagePathId $featureImagePathId
     * @param ProductId $productId
     * @param ProductAttributeValueId|null $productAttributeValueId
     * @param bool $isAvatar
     */
    public function __construct(FeatureImagePathId $featureImagePathId, ProductId $productId, ?ProductAttributeValueId $productAttributeValueId, bool $isAvatar)
    {
        $this->featureImagePathId = $featureImagePathId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->isAvatar = $isAvatar;
    }

    /**
     * @return \App\Bundle\ProductBundle\Domain\Model\FeatureImagePathId
     */
    public function getFeatureImagePathId(): FeatureImagePathId
    {
        return $this->featureImagePathId;
    }

    /**
     * @return \App\Bundle\ProductBundle\Domain\Model\ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId|null
     */
    public function getProductAttributeValueId(): ?ProductAttributeValueId
    {
        return $this->productAttributeValueId;
    }

    /**
     * @return bool
     */
    public function isAvatar(): bool
    {
        return $this->isAvatar;
    }
}
