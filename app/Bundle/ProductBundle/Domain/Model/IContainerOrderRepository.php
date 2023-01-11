<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IContainerOrderRepository
{
    /**
     * @param ContainerOrder $containerOrder
     * @return ContainerOrderId|null
     */
    public function create(ContainerOrder $containerOrder): ?ContainerOrderId;
}
