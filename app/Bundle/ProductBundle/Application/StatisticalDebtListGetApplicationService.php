<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryCriteria;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalDebtCriteria;

class StatisticalDebtListGetApplicationService
{
    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(IDebtHistoryRepository $debtHistoryRepository)
    {
        $this->debtHistoryRepository = $debtHistoryRepository;
    }

    /**
     * @param StatisticalDebtListGetCommand $command
     * @return DebtListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(StatisticalDebtListGetCommand $command): DebtListGetResult
    {
        $criteria = new StatisticalDebtCriteria(
            !is_null($command->date) ? SettingDate::fromTimeStamps($command->date) : null,
            !is_null($command->startDate) ? SettingDate::fromTimeStamps($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromTimeStamps($command->endDate) : null,
        );
        $debts = $this->debtHistoryRepository->findAllCurrentByCustomer($criteria);
        $debtResults = [];
        foreach ($debts as $debt) {
            $customer = $this->customerRepository->findById($debt->getCustomerId());
            $user = $this->userRepository->findById(new UserId($debt->getUserId()->asString()));
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
                !is_null($debt->getOrderId()) ? $debt->getOrderId()->asString() : null,
                !is_null($debt->getContainerOrderId()) ? $debt->getContainerOrderId()->asString() : null,
                !is_null($debt->getVatId()) ? $debt->getVatId()->asString() : null,
                !is_null($debt->getPaymentId()) ? $debt->getPaymentId()->asString() : null,
                !is_null($debt->getOtherDebtId()) ? $debt->getOtherDebtId()->asString() : null,
                $debt->getNumberOfMoney(),
                $debt->getUpdateDate()->asTimeStamps(),
                $debt->getMonetaryUnitType()->getValue(),
                $debt->getComment(),
                $debt->getVersion()
            );
        }

        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new DebtListGetResult($debtResults, $paginationResult);
    }
}
