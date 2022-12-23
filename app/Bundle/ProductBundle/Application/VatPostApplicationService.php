<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\DebtHistory;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryId;
use App\Bundle\ProductBundle\Domain\Model\DebtHistoryUpdateType;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IVatRepository;
use App\Bundle\ProductBundle\Domain\Model\MonetaryUnitType;
use App\Bundle\ProductBundle\Domain\Model\OrderPaymentStatus;
use App\Bundle\ProductBundle\Domain\Model\UserId;
use App\Bundle\ProductBundle\Domain\Model\Vat;
use App\Bundle\ProductBundle\Domain\Model\VatId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VatPostApplicationService
{
    /**
     * @var IVatRepository
     */
    private IVatRepository $vatRepository;

    /**
     * @var IDebtHistoryRepository
     */
    private IDebtHistoryRepository $debtHistoryRepository;

    /**
     * @param IVatRepository $vatRepository
     * @param IDebtHistoryRepository $debtHistoryRepository
     */
    public function __construct(IVatRepository $vatRepository, IDebtHistoryRepository $debtHistoryRepository)
    {
        $this->vatRepository = $vatRepository;
        $this->debtHistoryRepository = $debtHistoryRepository;
    }

    /**
     * @param VatPostCommand $command
     * @return VatPostResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(VatPostCommand $command): VatPostResult
    {
        $vatId = VatId::newId();
        $customerId = new CustomerId($command->customerId);
        $userId = new UserId($command->userId);
        $vat = new Vat(
            $vatId,
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
            true,
            DebtHistoryUpdateType::fromType(DebtHistoryUpdateType::VAT),
            null,
            null,
            $vatId,
            null,
            null,
            $command->cost,
            $command->date,
            MonetaryUnitType::fromValue($command->monetaryUnitType),
            !is_null($currentDebt) ? $currentDebt->getVersion() + 1 : 1
        );

        DB::beginTransaction();
        try {
            $vatId = $this->vatRepository->create($vat);
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

        return new VatPostResult($vatId->__toString());
    }
}
