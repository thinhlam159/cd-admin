<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Domain\Model\CarbonSettingDate;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\StatisticalDebtCriteria;
use Carbon\Carbon;

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
     */
    public function handle(StatisticalDebtListGetCommand $command): StatisticalDebtListGetResult
    {
        $targetDate = CarbonSettingDate::fromYmdHis($command->date);
        $revenuesDay = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $targetDate->getSubDay($i);
            $criteria = new StatisticalDebtCriteria(
                $date
            );
            $debts = $this->debtHistoryRepository->findAllByStatistical($criteria);
            $total = 0;
            foreach ($debts as $debt) {
                $total += $debt->getNumberOfMoney();
            }
            $revenuesDay[] = new StatisticalRevenuesDayResult(
                $date->asString(),
                $total
            );
        }

        return new StatisticalDebtListGetResult($revenuesDay);
    }
}
