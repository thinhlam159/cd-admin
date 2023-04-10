<?php
namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\RecordNotFoundException;
use App\Bundle\Common\Domain\Model\TransactionException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CustomerPutApplicationService
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
     * @param \App\Bundle\Admin\Application\CustomerPutCommand $command command
     * @return \App\Bundle\Admin\Application\CustomerPutResult
     */
    public function handle(CustomerPutCommand $command): CustomerPutResult
    {
        $customerId = new CustomerId($command->customerId);
        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            throw new RecordNotFoundException(MessageConst::NOT_FOUND['message']);
        }

//        $existingEmail = $this->customerRepository->checkExistingEmail($command->email, $customerId);
//        if ($existingEmail) {
//            throw new RecordNotFoundException(MessageConst::EXISTING_EMAIL['message']);
//        }

        $customer= new Customer(
            $customerId,
            $command->customerName,
            $command->email,
        );
        $customer->setPhone($command->phone);
        $customer->setAddress($command->address);
        $customer->setIsActive($command->isActive);

        DB::beginTransaction();
        try {
            $customerId = $this->customerRepository->update($customer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Update customer failed!');
        }

        return new CustomerPutResult(
            $customerId->__toString()
        );
    }
}
