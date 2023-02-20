<?php

namespace App\Bundle\ProductBundle\Application;

class ProductPostCommand
{

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string|null
     */
    public ?string $code;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * @var string
     */
    public string $categoryId;

    /**
     * @var string
     */
    public string $price;

    /**
     * @var string
     */
    public string $measureUnitType;

    /**
     * @var string
     */
    public string $noticePriceType;

    /**
     * @param string $name
     * @param string|null $code
     * @param string|null $description
     * @param string $categoryId
     * @param string $price
     * @param string $measureUnitType
     * @param string $noticePriceType
     */
    public function __construct(string $name, ?string $code, ?string $description, string $categoryId, string $price, string $measureUnitType, string $noticePriceType)
    {
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->measureUnitType = $measureUnitType;
        $this->noticePriceType = $noticePriceType;
    }
}
