<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Common\Constants\MessageConst;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\ProductBundle\Domain\Model\ContainerOrderId;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;
use App\Bundle\ProductBundle\Domain\Model\IDebtHistoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContainerOrderCancelPutApplicationService
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
     * @param ContainerOrderCancelPutCommand $command
     * @return ContainerOrderCancelPutResult
     * @throws InvalidArgumentException
     * @throws TransactionException
     */
    public function handle(ContainerOrderCancelPutCommand $command): ContainerOrderCancelPutResult
    {
        $containerOderId = new ContainerOrderId($command->containerOrderId);
        $containerOder = $this->containerOrderRepository->findById($containerOderId);
        if (!$containerOder) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }

        $debt = $this->debtHistoryRepository->findByContainerOrderId($containerOderId);
        if (!$debt) {
            throw new InvalidArgumentException(MessageConst::NO_RECORD['message']);
        }

        DB::beginTransaction();
        try {
            $updateResult = $this->containerOrderRepository->deleteById($containerOderId);
            $result = $this->debtHistoryRepository->deleteById($debt->getDebtHistoryId());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException($e->getMessage());
        }

        return new ContainerOrderCancelPutResult($containerOderId->__toString());
    }
}
