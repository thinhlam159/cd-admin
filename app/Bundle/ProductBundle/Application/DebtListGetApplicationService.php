<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\DealerId as AdminDealerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IDealerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DealerId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryCriteria;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IImportGoodRepository;
use App\Bundle\ProductBundle\Domain\Model\ImportGoodCriteria;
use App\Bundle\ProductBundle\Domain\Model\IProductAttributeValueRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductInventoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IProductRepository;
use App\Bundle\ProductBundle\Domain\Model\ProductAttributeValueId;
use App\Bundle\ProductBundle\Domain\Model\ProductId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;

class DebtListGetApplicationService
{
    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @param IDebtHistoryRepository $debtHistoryRepository
     * @param ICustomerRepository $customerRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(
        IDebtHistoryRepository $debtHistoryRepository,
        ICustomerRepository $customerRepository,
        IUserRepository $userRepository
    )
    {
        $this->debtHistoryRepository = $debtHistoryRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param DebtListGetCommand $command
     * @return DebtListGetResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(DebtListGetCommand $command): DebtListGetResult
    {
        $criteria = new DebtHistoryCriteria(
            !is_null($command->customerId) ? new ProductId($command->customerId) : null,
            $command->keyword,
        );
        [$debts, $pagination] = $this->debtHistoryRepository->findAllCurrentByCustomer($criteria);
        $debtResults = [];
        foreach ($debts as $debt) {
            $customer = $this->customerRepository->findById($debt->getCustomerId());
            $user = $this->userRepository->findById($debt->getUserId());
            $debtResults[] = new DebtResult(
                $debt->getDebtHistoryId()->asString(),
                $customer->getCustomerId()->asString(),
                $customer->getCustomerName(),
                $user->getUserId()->asString(),
                $user->getUserName(),
                $debt->getTotalDebt(),
                $debt->getTotalPayment(),
                $debt->isCurrent(),
                $debt->getDebtHistoryUpdateType()->getValue(),
                $debt->getOrderId()->asString(),
                $debt->getContainerOrderId()->asString(),
                $debt->getVatId()->asString(),
                $debt->getPaymentId()->asString(),
                $debt->getOtherDebtId()->asString(),
                $debt->getNumberOfMoney(),
                $debt->getUpdateDate(),
                $debt->getMonetaryUnitType()->getValue(),
                $debt->getVersion()
            );
        }

        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new DebtListGetResult($debtResults, $paginationResult);
    }
}
