<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class StatisticalDebtCriteria
{
    /**
     * @var SettingDate|null
     */
    private ?SettingDate $date;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $startDate;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $endDate;

    /**
     * @param SettingDate|null $date
     * @param SettingDate|null $startDate
     * @param SettingDate|null $endDate
     */
    public function __construct(?SettingDate $date, ?SettingDate $startDate, ?SettingDate $endDate)
    {
        $this->date = $date;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return SettingDate|null
     */
    public function getDate(): ?SettingDate
    {
        return $this->date;
    }

    /**
     * @return SettingDate|null
     */
    public function getStartDate(): ?SettingDate
    {
        return $this->startDate;
    }

    /**
     * @return SettingDate|null
     */
    public function getEndDate(): ?SettingDate
    {
        return $this->endDate;
    }
}
