<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\Pagination;

interface IDebtHistoryRepository
{
    /**
     * @param DebtHistory $debtHistory
     * @return DebtHistoryId|null
     */
    public function create(DebtHistory $debtHistory): ?DebtHistoryId;

    /**
     * @param CustomerId $customerId
     * @return DebtHistory|null
     */
    public function findCurrentDebtByCustomerId(CustomerId $customerId): ?DebtHistory;

    /**
     * @param DebtHistoryCriteria $criteria
     * @return array{DebtHistory[], Pagination}
     */
    public function findAllCurrentByCustomer(DebtHistoryCriteria $criteria): array;

    /**
     * @param CustomerDebtHistoryCriteria $criteria
     * @return array{DebtHistory[], Pagination}
     */
    public function findAllHistoryByCustomer(CustomerDebtHistoryCriteria $criteria): array;
}
