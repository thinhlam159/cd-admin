<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Constants\DateTimeConst;
use DateInterval;
use DatePeriod;
use DateTime;

final class SettingDateRange
{
    /**
     * @var DateTime
     */
    private DateTime $startDate;

    /**
     * @var DateTime
     */
    private DateTime $endDate;

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     */
    public function __construct(DateTime $startDate, DateTime $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->getStartDate()} - {$this->getEndDate()}";
    }

    /**
     * @return string[]
     */
    public function getDatesFromRange(): array
    {
        $dates = [];
        $interval = new DateInterval('P1D');
        $endDate = new DateTime($this->endDate->asString());
        $endDate->add($interval);
        $period = new DatePeriod(new DateTime($this->startDate->asString()), $interval, $endDate);
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $startDateTime = $this->getStartDate();
        $endDateTime = $this->getEndDate();

        return $endDateTime >= $startDateTime;
    }
}
