<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class ProductAttributePrice
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributePriceId
     */
    private ProductAttributePriceId $productAttributePriceId;

    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId
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
     * @var NoticePriceType
     */
    private NoticePriceType $noticePriceType;

    /**
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @param ProductAttributePriceId $productAttributePriceId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param int $price
     * @param MonetaryUnitType $monetaryUnitType
     * @param NoticePriceType $noticePriceType
     * @param bool $isCurrent
     */
    public function __construct(
        ProductAttributePriceId $productAttributePriceId,
        ProductAttributeValueId $productAttributeValueId,
        int $price,
        MonetaryUnitType $monetaryUnitType,
        NoticePriceType $noticePriceType,
        bool $isCurrent
    )
    {
        $this->productAttributePriceId = $productAttributePriceId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->price = $price;
        $this->monetaryUnitType = $monetaryUnitType;
        $this->noticePriceType = $noticePriceType;
        $this->isCurrent = $isCurrent;
    }

    /**
     * @return ProductAttributePriceId
     */
    public function getProductAttributePriceId(): ProductAttributePriceId
    {
        return $this->productAttributePriceId;
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
     * @return NoticePriceType
     */
    public function getNoticePriceType(): NoticePriceType
    {
        return $this->noticePriceType;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    /**
     * @return int
     */
    public function getStandardPrice() : int
    {
        return $this->price / $this->noticePriceType->getAmountValue();
    }

    /**
     * @param int $actualSellingPrice
     * @return int
     */
    public function calculateSellingPrice(int $actualSellingPrice) : int
    {
        return $actualSellingPrice / $this->noticePriceType->getAmountValue();
    }
}
