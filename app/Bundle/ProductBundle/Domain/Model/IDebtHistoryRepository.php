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
    public function findAllHistoryByCustomerId(CustomerDebtHistoryCriteria $criteria): array;

    /**
     * @param DebtHistoryId $debtHistoryId
     * @return bool
     */
    public function updateCurrentDebtHistory(DebtHistoryId $debtHistoryId): bool;

    /**
     * @param DebtHistory $debtHistory
     * @return DebtHistoryId|null
     */
    public function update(DebtHistory $debtHistory): ?DebtHistoryId;

    /**
     * @param DebtHistory $debtHistory
     * @return DebtHistoryId|null
     */
    public function initCustomerDebtHistory(DebtHistory $debtHistory): ?DebtHistoryId;

    /**
     * @param DebtsCustomerExcelCriteria $criteria
     * @return DebtHistory[]
     */
    public function findAllHistoryByCustomerId2(DebtsCustomerExcelCriteria $criteria): array;
}
