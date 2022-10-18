<?php
namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\RecordNotFoundException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\Common\Constants\MessageConst;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CustomerDeleteApplicationService
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
     * @param \App\Bundle\Admin\Application\CustomerDeleteCommand $command command
     * @return \App\Bundle\Admin\Application\CustomerPutResult
     */
    public function handle(CustomerDeleteCommand $command): CustomerPutResult
    {
        $userId = new CustomerId($command->customerId);
        $user = $this->customerRepository->findById($userId);
        if (!$user) {
            throw new RecordNotFoundException(MessageConst::NOT_FOUND['message']);
        }

        DB::beginTransaction();
        try {
            $result = $this->customerRepository->delete($userId);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Delete customer failed!');
        }

        return new CustomerPutResult(
            $userId->__toString()
        );
    }
}
