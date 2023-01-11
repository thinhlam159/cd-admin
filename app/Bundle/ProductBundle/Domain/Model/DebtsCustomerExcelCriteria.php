<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class DebtsCustomerExcelCriteria
{
    /**
     * @var CustomerId|null
     */
    private ?CustomerId $customerId;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $startDate;

    /**
     * @var SettingDate|null
     */
    private ?SettingDate $endDate;

    /**
     * @param CustomerId|null $customerId
     * @param SettingDate|null $startDate
     * @param SettingDate|null $endDate
     */
    public function __construct(?CustomerId $customerId, ?SettingDate $startDate, ?SettingDate $endDate)
    {
        $this->customerId = $customerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return CustomerId|null
     */
    public function getCustomerId(): ?CustomerId
    {
        return $this->customerId;
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
