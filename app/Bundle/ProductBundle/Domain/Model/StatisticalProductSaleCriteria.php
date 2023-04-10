<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class StatisticalProductSaleCriteria
{
    /**
     * @var CategoryId |null
     */
    private ?CategoryId $categoryId;

    /**
     * @var ProductAttributeValueId |null
     */
    private ?ProductAttributeValueId $productAttributeValueId;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $startDate;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $endDate;

    /**
     * @param CategoryId|null $categoryId
     * @param ProductAttributeValueId|null $productAttributeValueId
     * @param SettingDate|null $startDate
     * @param SettingDate|null $endDate
     */
    public function __construct(
        ?CategoryId $categoryId,
        ?ProductAttributeValueId $productAttributeValueId,
        ?SettingDate $startDate,
        ?SettingDate $endDate
    )
    {
        $this->categoryId = $categoryId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return CategoryId|null
     */
    public function getCategoryId(): ?CategoryId
    {
        return $this->categoryId;
    }

    /**
     * @return ProductAttributeValueId|null
     */
    public function getProductAttributeValueId(): ?ProductAttributeValueId
    {
        return $this->productAttributeValueId;
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
