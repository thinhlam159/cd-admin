<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class ExportGoodCriteria
{
    /**
     * @var ProductId|null
     */
    private ?ProductId $productId;

    /**
     * @var ProductAttributeValueId|null
     */
    private ?ProductAttributeValueId $productAttributeValueId;

    /**
     * @var string|null
     */
    private ?string $keyword;

    /**
     * @var string|null
     */
    private ?string $sort;

    /**
     * @var string|null
     */
    private ?string $order;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $startDate;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $endDate;

    /**
     * @param ProductId|null $productId
     * @param ProductAttributeValueId|null $productAttributeValueId
     * @param string|null $keyword
     * @param string|null $sort
     * @param string|null $order
     * @param SettingDate|null $startDate
     * @param SettingDate|null $endDate
     */
    public function __construct(
        ?ProductId $productId,
        ?ProductAttributeValueId $productAttributeValueId,
        ?string $keyword,
        ?string $sort,
        ?string $order,
        ?SettingDate $startDate,
        ?SettingDate $endDate
    )
    {
        $this->productId = $productId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->keyword = $keyword;
        $this->sort = $sort;
        $this->order = $order;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return ProductId|null
     */
    public function getProductId(): ?ProductId
    {
        return $this->productId;
    }

    /**
     * @return ProductAttributeValueId|null
     */
    public function getProductAttributeValueId(): ?ProductAttributeValueId
    {
        return $this->productAttributeValueId;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @return string|null
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @return SettingDate|null
     */
    public function getStartDate(): ?SettingDate
    {
        return $this->startDate;
    }

    /**
     * @return SettingDate|null
     */
    public function getEndDate(): ?SettingDate
    {
        return $this->endDate;
    }
}
