<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IOrderRepository
{
    /**
     * @param Order $order
     * @return OrderId|null
     */
    public function create(Order $order): ?OrderId;
}
