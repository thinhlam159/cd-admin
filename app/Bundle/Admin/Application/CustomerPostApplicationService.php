<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerPostApplicationService
{
    private $customerRepository;

    public function __construct(ICustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function handle(CustomerPostCommand $command): CustomerPostResult
    {
        $existingEmail = $this->customerRepository->checkExistingEmail($command->email);
        if (!$existingEmail) {
            throw new InvalidArgumentException('Existing Email!');
        }
        $customerId = CustomerId::newId();
        $customer = new Customer(
            $customerId,
            $command->customerName,
            $command->email,
        );
        $customer->setPassword($command->password);
        $customer->setPhone($command->phone);
        $customer->setIsActive(false);

        DB::beginTransaction();
        try {
            $customerId = $this->customerRepository->create($customer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add customer fail!');
        }

        return new CustomerPostResult($customerId->__toString());
    }
}
