<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributePriceRepository
{

    /**
     * @param ProductAttributePrice $productAttributePrice
     * @return ProductAttributePriceId
     */
    public function create(ProductAttributePrice $productAttributePrice): ProductAttributePriceId;
}
