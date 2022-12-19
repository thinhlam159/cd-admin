<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IContainerOrderRepository
{
    /**
     * @param Product $category
     * @return ProductId
     */
    public function create(Product $category): ProductId;
}
