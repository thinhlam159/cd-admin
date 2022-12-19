<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\CustomerDebtHistoryCriteria;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductId;

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
        $criteria = new CustomerDebtHistoryCriteria(
            !is_null($command->customerId) ? new ProductId($command->customerId) : null,
            $command->keyword,
        );
        [$debtHistories, $pagination] = $this->debtHistoryRepository->findAllHistoryByCustomer($criteria);
        $debtResults = [];
        foreach ($debtHistories as $debt) {
            $customer = $this->customerRepository->findById($debt->getCustomerId());
            $user = $this->userRepository->findById($debt->getUserId());
            $debtResults[] = new DebtResult(
                $debt->getDebtHistoryId()->asString(),
                $customer->getCustomerId()->asString(),
                $customer->getCustomerName(),
                $user->getUserId()->asString(),
                $user->getUserName(),
                $debt->getTotalDebt(),
                $debt->getTotalPayment(),
                $debt->isCurrent(),
                $debt->getDebtHistoryUpdateType()->getValue(),
                $debt->getOrderId()->asString(),
                $debt->getContainerOrderId()->asString(),
                $debt->getVatId()->asString(),
                $debt->getPaymentId()->asString(),
                $debt->getOtherDebtId()->asString(),
                $debt->getNumberOfMoney(),
                $debt->getUpdateDate(),
                $debt->getMonetaryUnitType()->getValue(),
                $debt->getVersion()
            );
        }

        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new CustomerDebtHistoryListGetResult($debtResults, $paginationResult);
    }
}
