<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\CustomerDebtHistoryCriteria;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;

class CustomerCurrentDebtGetApplicationService
{
    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @param IDebtHistoryRepository $debtHistoryRepository
     * @param ICustomerRepository $customerRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(
        IDebtHistoryRepository $debtHistoryRepository,
        ICustomerRepository $customerRepository,
        IUserRepository $userRepository
    )
    {
        $this->debtHistoryRepository = $debtHistoryRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param CustomerCurrentDebtGetCommand $command
     * @return CustomerDebtHistoryListGetResult
     * @throws InvalidArgumentException
     */
    public function handle(CustomerCurrentDebtGetCommand $command): CustomerCurrentDebtGetResult
    {
        $customerId = new CustomerId($command->customerId);
        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        $debt = $this->debtHistoryRepository->findCurrentDebtByCustomerId($customerId);

        return new CustomerCurrentDebtGetResult(
            $debt->getDebtHistoryId()->asString(),
            $customer->getCustomerId()->asString(),
            $customer->getCustomerName(),
            $debt->getTotalDebt(),
            $debt->getTotalPayment(),
            $debt->getRestDebt(),
            $debt->isCurrent(),
            $debt->getDebtHistoryUpdateType()->getValue(),
            !is_null($debt->getOrderId()) ? $debt->getOrderId()->asString() : null,
            !is_null($debt->getContainerOrderId()) ? $debt->getContainerOrderId()->asString() : null,
            !is_null($debt->getVatId()) ? $debt->getVatId()->asString() : null,
            !is_null($debt->getPaymentId()) ? $debt->getPaymentId()->asString() : null,
            !is_null($debt->getOtherDebtId()) ? $debt->getOtherDebtId()->asString() : null,
            $debt->getNumberOfMoney(),
            $debt->getUpdateDate(),
            $debt->getMonetaryUnitType()->getValue(),
            $debt->getVersion()
        );
    }
}
