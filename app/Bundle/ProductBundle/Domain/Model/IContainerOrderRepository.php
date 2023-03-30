<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\Pagination;

interface IContainerOrderRepository
{
    /**
     * @param ContainerOrder $containerOrder
     * @return ContainerOrderId|null
     */
    public function create(ContainerOrder $containerOrder): ?ContainerOrderId;

    /**
     * @param CustomerId $customerId
     * @return array{ContainerOrder[], Pagination}
     */
    public function findAllByCustomerId(CustomerId $customerId): array;

    /**
     * @param ContainerOrderId $containerOrderId
     * @return ContainerOrder|null
     */
    public function findById(ContainerOrderId $containerOrderId): ?ContainerOrder;

    /**
     * @param ContainerOrderId $containerOrderId
     * @return bool
     */
    public function deleteById(ContainerOrderId $containerOrderId): bool;
}
