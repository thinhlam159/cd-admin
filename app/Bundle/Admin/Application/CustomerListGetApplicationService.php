<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Application\PaginationResult;

class CustomerListGetApplicationService
{
    private $customerRepository;

    public function __construct(ICustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param \App\Bundle\Admin\Application\CustomerListGetCommand $command
     * @return \App\Bundle\Admin\Application\CustomerListGetResult
     */
    public function handle(CustomerListGetCommand $command): CustomerListGetResult
    {
        [$customers, $pagination] = $this->customerRepository->findAll();
        $customerResults = [];
        foreach ($customers as $customer) {
            $customerResults[] = new CustomerResult(
                $customer->getCustomerId()->__toString(),
                $customer->getCustomerName(),
                $customer->getEmail(),
                $customer->getPhone(),
                $customer->getIsActive(),
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new CustomerListGetResult(
            $customerResults,
            $paginationResult
        );
    }
}
