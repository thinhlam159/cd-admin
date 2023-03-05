<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\ProductBundle\Domain\Model\CustomerCriteria;

class CustomerAllListGetApplicationService
{
    private $customerRepository;

    public function __construct(ICustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param \App\Bundle\Admin\Application\CustomerAllListGetCommand $command
     * @return \App\Bundle\Admin\Application\CustomerAllListGetResult
     */
    public function handle(CustomerAllListGetCommand $command): CustomerAllListGetResult
    {
        $criteria = new CustomerCriteria(
            null,
            $command->keyword
        );
        $customers = $this->customerRepository->findAllNotPaginate($criteria);
        $customerResults = [];
        foreach ($customers as $customer) {
            $customerResults[] = new CustomerResult(
                $customer->getCustomerId()->__toString(),
                $customer->getCustomerName(),
                $customer->getEmail(),
                $customer->getAddress(),
                $customer->getPhone(),
                $customer->getIsActive(),
            );
        }

        return new CustomerAllListGetResult(
            $customerResults
        );
    }
}
