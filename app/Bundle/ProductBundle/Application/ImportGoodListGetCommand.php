<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $productId;
    /**
     * @var string|null
     */
    public ?string $dealerId;
    /**
     * @var string|null
     */
    public ?string $productAttributeValueId;
    /**
     * @var string|null
     */
    public ?string $keyword;
    /**
     * @var string|null
     */
    public ?string $sort;
    /**
     * @var string|null
     */
    public ?string $order;
    /**
     * @var string|null
     */
    public ?string $startDate;
    /**
     * @var string|null
     */
    public ?string $endDate;

    /**
     * @param string|null $productId
     * @param string|null $dealerId
     * @param string|null $productAttributeValueId
     * @param string|null $keyword
     * @param string|null $sort
     * @param string|null $order
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(?string $productId, ?string $dealerId, ?string $productAttributeValueId, ?string $keyword, ?string $sort, ?string $order, ?string $startDate, ?string $endDate)
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
}
