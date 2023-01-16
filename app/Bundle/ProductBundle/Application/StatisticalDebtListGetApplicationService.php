<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
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
     * @return StatisticalDebtListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(StatisticalDebtListGetCommand $command): StatisticalDebtListGetResult
    {
        $criteria = new StatisticalDebtCriteria(
            !is_null($command->date) ? SettingDate::fromTimeStamps($command->date) : null,
            !is_null($command->startDate) ? SettingDate::fromTimeStamps($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromTimeStamps($command->endDate) : null,
        );
        $debts = $this->debtHistoryRepository->findAllByStatistical($criteria);
        $debtResults = [];
        foreach ($debts as $debt) {
            $debtResults[] = new DebtResult(
                $debt->getDebtHistoryId()->asString(),
                '',
                '',
                '',
                '',
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

        return new StatisticalDebtListGetResult($debtResults);
    }
}
