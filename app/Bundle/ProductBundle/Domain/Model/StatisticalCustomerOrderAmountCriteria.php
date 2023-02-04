<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class StatisticalCustomerOrderAmountCriteria
{
    /**
     * @var CustomerId|null
     */
    private ?CustomerId $customerId;

    /**
     * @var string|null
     */
    private ?string $keyword;

    /**
     * @var string|null
     */
    private ?string $order;

    /**
     * @var string|null
     */
    private ?string $sort;

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
     * @param string|null $keyword
     * @param string|null $order
     * @param string|null $sort
     * @param SettingDate|null $startDate
     * @param SettingDate|null $endDate
     */
    public function __construct(
        ?CustomerId $customerId,
        ?string $keyword,
        ?string $order,
        ?string $sort,
        ?SettingDate $startDate,
        ?SettingDate $endDate
    )
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
        $this->order = $order;
        $this->sort = $sort;
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
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @return string|null
     */
    public function getSort(): ?string
    {
        return $this->sort;
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
