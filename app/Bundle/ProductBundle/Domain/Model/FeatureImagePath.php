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
     * @var string
     */
    private string $path;

    /**
     * @param FeatureImagePathId $featureImagePathId
     * @param ProductId $productId
     * @param ProductAttributeValueId|null $productAttributeValueId
     * @param bool $isAvatar
     * @param string $path
     */
    public function __construct(FeatureImagePathId $featureImagePathId, ProductId $productId, ?ProductAttributeValueId $productAttributeValueId, bool $isAvatar, string $path)
    {
        $this->featureImagePathId = $featureImagePathId;
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->isAvatar = $isAvatar;
        $this->path = $path;
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

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
