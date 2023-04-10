<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;

class ContainerOrderCustomerListGetApplicationService
{
    /**
     * @var IContainerOrderRepository
     */
    private IContainerOrderRepository $containerOrderRepository;

    /**
     * @param IContainerOrderRepository $containerOrderRepository
     */
    public function __construct(IContainerOrderRepository $containerOrderRepository)
    {
        $this->containerOrderRepository = $containerOrderRepository;
    }

    /**
     * @param ContainerOrderCustomerListGetCommand $command
     * @return ContainerOrderCustomerListGetResult
     * @throws InvalidArgumentException
     */
    public function handle(ContainerOrderCustomerListGetCommand $command): ContainerOrderCustomerListGetResult
    {
        $customerId = new CustomerId($command->customerId);
        [$containerOrders, $pagination] = $this->containerOrderRepository->findAllByCustomerId($customerId);
        $containerOrderResults = [];
        foreach ($containerOrders as $order) {
            $containerOrderResults[] = new ContainerOrderResult(
                $order->getContainerOrderId()->asString(),
                $order->getCost(),
                $order->getMonetaryUnitType()->getValue(),
                $order->getComment(),
                $order->getCustomerId()->asString(),
                $order->getUserId()->asString(),
                $order->getArisingDate(),
                $order->getPaymentStatus()->getValue(),
            );
        }

        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new ContainerOrderCustomerListGetResult($paginationResult, $containerOrderResults);
    }
}
