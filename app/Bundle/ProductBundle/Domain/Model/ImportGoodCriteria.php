<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class ImportGoodCriteria
{
    /**
     * @var ProductId
     */
    private ProductId $productId;
    /**
     * @var DealerId
     */
    private DealerId $dealerId;
    /**
     * @var ProductAttributeValueId
     */
    private ProductAttributeValueId $productAttributeValueId;
    /**
     * @var string
     */
    private string $keyword;
    /**
     * @var string
     */
    private string $sort;
    /**
     * @var string
     */
    private string $order;
    /**
     * @var SettingDate
     */
    private SettingDate $startDate;
    /**
     * @var SettingDate
     */
    private SettingDate $endDate;

    /**
     * @param ProductId $productId
     * @param DealerId $dealerId
     * @param ProductAttributeValueId $productAttributeValueId
     * @param string $keyword
     * @param string $sort
     * @param string $order
     * @param SettingDate $startDate
     * @param SettingDate $endDate
     */
    public function __construct(ProductId $productId, DealerId $dealerId, ProductAttributeValueId $productAttributeValueId, string $keyword, string $sort, string $order, SettingDate $startDate, SettingDate $endDate)
    {
        $this->productId = $productId;
        $this->dealerId = $dealerId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->keyword = $keyword;
        $this->sort = $sort;
        $this->order = $order;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return DealerId
     */
    public function getDealerId(): DealerId
    {
        return $this->dealerId;
    }

    /**
     * @return ProductAttributeValueId
     */
    public function getProductAttributeValueId(): ProductAttributeValueId
    {
        return $this->productAttributeValueId;
    }

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @return SettingDate
     */
    public function getStartDate(): SettingDate
    {
        return $this->startDate;
    }

    /**
     * @return SettingDate
     */
    public function getEndDate(): SettingDate
    {
        return $this->endDate;
    }
}
