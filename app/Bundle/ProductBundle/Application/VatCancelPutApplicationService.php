<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrderId;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use App\Bundle\ProductBundle\Domain\Model\IVatRepository;
use App\Bundle\ProductBundle\Domain\Model\VatId;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VatCancelPutApplicationService
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
     * @param VatCancelPutCommand $command
     * @return VatCancelPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(VatCancelPutCommand $command): VatCancelPutResult
    {
        $vatId = new VatId($command->vatId);
        $vat = $this->vatRepository->findById($vatId);
        if (!$vat) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }

        $debt = $this->debtHistoryRepository->findByVatId($vatId);
        if (!$debt) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }

        DB::beginTransaction();
        try {
            $updateResult = $this->vatRepository->deleteById($vatId);
            $result = $this->debtHistoryRepository->deleteById($debt->getDebtHistoryId());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new VatCancelPutResult($vatId->__toString());
    }
}
