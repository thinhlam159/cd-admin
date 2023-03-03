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
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;

class CustomerDebtHistoryListGetApplicationService
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
     * @param CustomerDebtHistoryListGetCommand $command
     * @return CustomerDebtHistoryListGetResult
     * @throws InvalidArgumentException
     */
    public function handle(CustomerDebtHistoryListGetCommand $command): CustomerDebtHistoryListGetResult
    {
        $customerId = new CustomerId($command->customerId);
        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        $criteria = new CustomerDebtHistoryCriteria(
            $customerId,
            $command->keyword,
        );
        $debtHistories = $this->debtHistoryRepository->findAllHistoryByCustomerId3($customerId);
        $debtResults = [];
        $totalDebt = 0;
        $totalPayment = 0;
        foreach ($debtHistories as $debt) {
            $customer = $this->customerRepository->findById($debt->getCustomerId());
            $user = $this->userRepository->findById(new UserId($debt->getUserId()->asString()));
            $debtResults[] = new DebtResult(
                $debt->getDebtHistoryId()->asString(),
                $customer->getCustomerId()->asString(),
                $customer->getCustomerName(),
                $user->getUserId()->asString(),
                $user->getUserName(),
                $debt->calculateTotalDebt($totalDebt),
                $debt->calculateTotalPayment($totalPayment),
                $debt->isCurrent(),
                $debt->getDebtHistoryUpdateType()->getValue(),
                !is_null($debt->getOrderId()) ? $debt->getOrderId()->asString() : null,
                !is_null($debt->getContainerOrderId()) ? $debt->getContainerOrderId()->asString() : null,
                !is_null($debt->getVatId()) ? $debt->getVatId()->asString() : null,
                !is_null($debt->getPaymentId()) ? $debt->getPaymentId()->asString() : null,
                !is_null($debt->getOtherDebtId()) ? $debt->getOtherDebtId()->asString() : null,
                $debt->getNumberOfMoney(),
                $debt->getUpdateDate()->asString(),
                $debt->getMonetaryUnitType()->getValue(),
                $debt->getComment(),
                $debt->getVersion()
            );
            $totalDebt = $debt->calculateTotalDebt($totalDebt);
            $totalPayment = $debt->calculateTotalPayment($totalPayment);
        }

        $paginationResult = new PaginationResult(
//            $pagination->getTotalPages(),
//            $pagination->getPerPage(),
//            $pagination->getCurrentPage(),
            1,
            1,
            1
        );

        return new CustomerDebtHistoryListGetResult($debtResults, $paginationResult);
    }
}
