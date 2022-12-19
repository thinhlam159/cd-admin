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
     * @param CustomerId|null $customerId
     * @param string|null $keyword
     */
    public function __construct(?CustomerId $customerId, ?string $keyword)
    {
        $this->customerId = $customerId;
        $this->keyword = $keyword;
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
}
