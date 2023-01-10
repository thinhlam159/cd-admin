<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtsCustomerExcelCriteria;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;

class DebtsCustomerExcelExportPostApplicationService
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
     * @param IDebtHistoryRepository $debtHistoryRepository debtHistoryRepository
     * @param ICustomerRepository $customerRepository customerRepository
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
     * @param DebtsCustomerExcelExportPostCommand $command
     * @return DebtsCustomerExcelOrderExportPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(DebtsCustomerExcelExportPostCommand $command): DebtsCustomerExcelOrderExportPostResult
    {
        $customerId = new CustomerId($command->customerId);
        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }
        $criteria = new DebtsCustomerExcelCriteria(
            $customerId,
            !is_null($command->startDate) ? SettingDate::fromYmdHis($command->startDate) : null,
            !is_null($command->endDate) ? SettingDate::fromYmdHis($command->endDate) : null,
        );
        $debtHistories = $this->debtHistoryRepository->findAllHistoryByCustomerId2($criteria);
        $debtResults = [];
        foreach ($debtHistories as $debt) {
            $customer = $this->customerRepository->findById($debt->getCustomerId());
            $user = $this->userRepository->findById(new UserId($debt->getUserId()->asString()));
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
                !is_null($debt->getOrderId()) ? $debt->getOrderId()->asString() : null,
                !is_null($debt->getContainerOrderId()) ? $debt->getContainerOrderId()->asString() : null,
                !is_null($debt->getVatId()) ? $debt->getVatId()->asString() : null,
                !is_null($debt->getPaymentId()) ? $debt->getPaymentId()->asString() : null,
                !is_null($debt->getOtherDebtId()) ? $debt->getOtherDebtId()->asString() : null,
                $debt->getNumberOfMoney(),
                $debt->getUpdateDate()->asTimeStamps(),
                $debt->getMonetaryUnitType()->getValue(),
                $debt->getComment(),
                $debt->getVersion()
            );
        }

        return new DebtsCustomerExcelOrderExportPostResult($debtResults);
    }
}
