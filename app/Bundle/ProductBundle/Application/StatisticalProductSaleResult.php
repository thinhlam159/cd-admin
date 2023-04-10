<?php

namespace App\Bundle\ProductBundle\Application;

class StatisticalProductSaleResult
{
    /**
     * @var string
     */
    public string $productId;

    /**
     * @var string
     */
    public string $productName;

    /**
     * @var string
     */
    public string $productCode;

    /**
     * @var int
     */
    public int $count;

    /**
     * @param string $productId
     * @param string $productName
     * @param string $productCode
     * @param int $count
     */
    public function __construct(string $productId, string $productName, string $productCode, int $count)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->count = $count;
    }
}
