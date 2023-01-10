<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrder;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrderId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IPaymentRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\Payment;
use App\Bundle\ProductBundle\Domain\Model\PaymentId;
use App\Bundle\ProductBundle\Domain\Model\SettingDate;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContainerOrderPostApplicationService
{
    /**
     * @var IContainerOrderRepository
     */
    private IContainerOrderRepository $containerOrderRepository;

    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param IContainerOrderRepository $containerOrderRepository
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(IContainerOrderRepository $containerOrderRepository, IDebtHistoryRepository $debtHistoryRepository)
    {
        $this->containerOrderRepository = $containerOrderRepository;
        $this->debtHistoryRepository = $debtHistoryRepository;
    }

    /**
     * @param ContainerOrderPostCommand $command
     * @return ContainerOrderPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ContainerOrderPostCommand $command): ContainerOrderPostResult
    {
        $containerOrderId = ContainerOrderId::newId();
        $customerId = new CustomerId($command->customerId);
        $userId = new UserId($command->userId);
        $containerOrder = new ContainerOrder(
            $containerOrderId,
            $command->cost,
            MonetaryUnitType::fromValue($command->monetaryUnitType),
            $command->comment,
            $customerId,
            $userId,
            OrderPaymentStatus::fromStatus(OrderPaymentStatus::PENDING),
            $command->date,
        );
        $currentDebt = $this->debtHistoryRepository->findCurrentDebtByCustomerId($customerId);

        $debtHistoryId = DebtHistoryId::newId();
        $newDebtHistory = new DebtHistory(
            $debtHistoryId,
            $customerId,
            $userId,
            !is_null($currentDebt) ? $currentDebt->getTotalDebt() + $command->cost : $command->cost,
            !is_null($currentDebt) ? $currentDebt->getTotalPayment() : 0,
            !is_null($currentDebt) ? $currentDebt->getRestDebt() + $command->cost : $command->cost,
            true,
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::CONTAINER_ORDER),
            null,
            $containerOrderId,
            null,
            null,
            null,
            $command->cost,
            SettingDate::fromTimeStamps($command->date),
            MonetaryUnitType::fromValue($command->monetaryUnitType),
            $command->comment,
            !is_null($currentDebt) ? $currentDebt->getVersion() + 1 : 1
        );

        DB::beginTransaction();
        try {
            $containerOrderId = $this->containerOrderRepository->create($containerOrder);
            if ($currentDebt) {
                $this->debtHistoryRepository->updateCurrentDebtHistory($currentDebt->getDebtHistoryId());
            }
            $this->debtHistoryRepository->create($newDebtHistory);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new ContainerOrderPostResult($containerOrderId->__toString());
    }
}
