<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IProductAttributeValueRepository
{
    /**
     * @param ProductAttributeValue $productAttribute
     * @return ProductAttributeValueId
     */
    public function create(ProductAttributeValue $productAttribute): ProductAttributeValueId;
}
