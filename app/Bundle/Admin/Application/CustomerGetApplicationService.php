<?php
namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\RecordNotFoundException;
use App\Bundle\Common\Constants\MessageConst;

final class CustomerGetApplicationService
{
    /**
     * @var \App\Bundle\Admin\Domain\Model\ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @param \App\Bundle\Admin\Domain\Model\ICustomerRepository $customerRepository customerRepository
     */
    public function __construct(
        ICustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param \App\Bundle\Admin\Application\CustomerGetCommand $command command
     * @return \App\Bundle\Admin\Application\CustomerGetResult
     */
    public function handle(CustomerGetCommand $command): CustomerGetResult
    {
        $customer = $this->customerRepository->findById(new CustomerId($command->customerId));
        if (!$customer) {
            throw new RecordNotFoundException(MessageConst::NOT_FOUND['message']);
        }

        return new CustomerGetResult(
            $customer->getCustomerId()->__toString(),
            $customer->getCustomerName(),
            $customer->getEmail(),
            $customer->getPhone(),
            $customer->getIsActive(),
        );
    }
}
