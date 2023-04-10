<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\CarbonSettingDate;

final class StatisticalDebtCriteria
{
    /**
     * @var CarbonSettingDate|null
     */
    private ?CarbonSettingDate $date;

    /**
     * @var CarbonSettingDate|null
     */
    private ?CarbonSettingDate $startDate;

    /**
     * @var CarbonSettingDate|null
     */
    private ?CarbonSettingDate $endDate;

    /**
     * @param CarbonSettingDate|null $date
     * @param CarbonSettingDate|null $startDate
     * @param CarbonSettingDate|null $endDate
     */
    public function __construct(?CarbonSettingDate $date, ?CarbonSettingDate $startDate = null, ?CarbonSettingDate $endDate = null)
    {
        $this->date = $date;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return CarbonSettingDate|null
     */
    public function getDate(): ?CarbonSettingDate
    {
        return $this->date;
    }

    /**
     * @return CarbonSettingDate|null
     */
    public function getStartDate(): ?CarbonSettingDate
    {
        return $this->startDate;
    }

    /**
     * @return CarbonSettingDate|null
     */
    public function getEndDate(): ?CarbonSettingDate
    {
        return $this->endDate;
    }
}
