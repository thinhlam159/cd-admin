<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\CarbonSettingDate;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\StatisticalDebtCriteria;

class StatisticalPeriodRevenueGetApplicationService
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
     * @param StatisticalPeriodRevenueGetCommand $command
     * @return StatisticalPeriodRevenueGetResult
     */
    public function handle(StatisticalPeriodRevenueGetCommand $command): StatisticalPeriodRevenueGetResult
    {
        $criteria = new StatisticalDebtCriteria(
            null,
            !is_null($command->startDate) ? CarbonSettingDate::fromYmdHis($command->startDate) : null,
            !is_null($command->endDate) ? CarbonSettingDate::fromYmdHis($command->endDate) : null,
        );
        $debts = $this->debtHistoryRepository->findAllByPeriodRevenue($criteria);

        $total = 0;
        foreach ($debts as $debt) {
            $total += $debt->getNumberOfMoney();
        }

        return new StatisticalPeriodRevenueGetResult($total);
    }
}
