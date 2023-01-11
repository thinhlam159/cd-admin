<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId as DebtUnitId;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerPostApplicationService
{
    /**
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param ICustomerRepository $customerRepository
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(ICustomerRepository $customerRepository, IDebtHistoryRepository $debtHistoryRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->debtHistoryRepository = $debtHistoryRepository;
    }

    public function handle(CustomerPostCommand $command): CustomerPostResult
    {
        $existingEmail = $this->customerRepository->checkExistingEmail($command->email);
        if ($existingEmail) {
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

        $debtHistory = new DebtHistory(
            DebtHistoryId::newId(),
            $customerId,
            new DebtUnitId(Auth::id()),
            0,
            0,
            0,
            true,
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::INIT),
            null,
            null,
            null,
            null,
            null,
            0,
            SettingDate::now(),
            MonetaryUnitType::fromType(MonetaryUnitType::VND),
            null,
            0
        );

        DB::beginTransaction();
        try {
            $customerId = $this->customerRepository->create($customer);
            $debtHistoryId = $this->debtHistoryRepository->initCustomerDebtHistory($debtHistory);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add customer fail!');
        }

        return new CustomerPostResult($customerId->__toString());
    }
}
