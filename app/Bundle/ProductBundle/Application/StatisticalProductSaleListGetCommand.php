<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalProductSaleListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $categoryId;

    /**
     * @var string|null
     */
    public ?string $productAttributeValueId;

    /**
     * @var string|null
     */
    public ?string $startDate;

    /**
     * @var string|null
     */
    public ?string $endDate;

    /**
     * @param string|null $categoryId
     * @param string|null $productAttributeValueId
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct(
        ?string $categoryId,
        ?string $productAttributeValueId,
        ?string $startDate,
        ?string $endDate
    )
    {
        $this->categoryId = $categoryId;
        $this->productAttributeValueId = $productAttributeValueId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}
