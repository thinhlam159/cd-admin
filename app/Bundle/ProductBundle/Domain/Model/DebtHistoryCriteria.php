<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;

final class DebtHistoryCriteria
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
     * @param CustomerId|null $customerId
     * @param string|null $keyword
     * @param string|null $order
     * @param string|null $sort
     */
    public function __construct(?CustomerId $customerId, ?string $keyword, ?string $order, ?string $sort)
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
        $this->order = $order;
        $this->sort = $sort;
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
}
