<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\ProductBundle\Application\ContainerOrderPostApplicationService;
use App\Bundle\ProductBundle\Application\ContainerOrderPostCommand;
use App\Bundle\ProductBundle\Application\CustomerDebtHistoryListGetApplicationService;
use App\Bundle\ProductBundle\Application\CustomerDebtHistoryListGetCommand;
use App\Bundle\ProductBundle\Application\DebtListGetApplicationService;
use App\Bundle\ProductBundle\Application\DebtListGetCommand;
use App\Bundle\ProductBundle\Application\PaymentPostApplicationService;
use App\Bundle\ProductBundle\Application\PaymentPostCommand;
use App\Bundle\ProductBundle\Application\VatPostApplicationService;
use App\Bundle\ProductBundle\Application\VatPostCommand;
use App\Bundle\ProductBundle\Infrastructure\ContainerOrderRepository;
use App\Bundle\ProductBundle\Infrastructure\DebtHistoryRepository;
use App\Bundle\ProductBundle\Infrastructure\PaymentRepository;
use App\Bundle\ProductBundle\Infrastructure\VatRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createPayment(Request $request)
    {
        $applicationService = new PaymentPostApplicationService(
            new PaymentRepository(),
            new DebtHistoryRepository()
        );

        $command = new PaymentPostCommand(
            $request->cost,
            $request->monetary_unit_type,
            !empty($request->comment) ? $request->comment : null,
            $request->customer_id,
            Auth::id(),
            $request->date,
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->paymentId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request request
     */
    public function createContainerOrderDebt(Request $request)
    {
        $applicationService = new ContainerOrderPostApplicationService(
            new ContainerOrderRepository(),
            new DebtHistoryRepository()
        );

        $command = new ContainerOrderPostCommand(
            $request->cost,
            $request->monetary_unit_type,
            !empty($request->comment) ? $request->comment : null,
            $request->customer_id,
            Auth::id(),
            $request->date,
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->containerOrderId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request request
     */
    public function createVatDebt(Request $request)
    {
        $applicationService = new VatPostApplicationService(
            new VatRepository,
            new DebtHistoryRepository
        );

        $command = new VatPostCommand(
            $request->cost,
            $request->monetary_unit_type,
            !empty($request->comment) ? $request->comment : null,
            $request->customer_id,
            Auth::id(),
            $request->date,
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->vatId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request request
     */
    public function getDebts(Request $request)
    {
        $applicationService = new DebtListGetApplicationService(
            new DebtHistoryRepository(),
            new CustomerRepository(),
            new UserRepository()
        );

        $command = new DebtListGetCommand(
            !empty($request->customer_id) ? $request->customer_id : null,
            !empty($request->keyword) ? $request->keyword : null,
        );

        $result = $applicationService->handle($command);
        $data = [];
        foreach ($result->debtResults as $debtResult) {
            $data[] = [
                'debt_history_id' => $debtResult->debtHistoryId,
                'customer_id' => $debtResult->customerId,
                'customer_name' => $debtResult->customerName,
                'user_id' => $debtResult->userId,
                'user_name' => $debtResult->userName,
                'total_debt' => $debtResult->totalDebt,
                'total_payment' => $debtResult->totalPayment,
                'updated_date' => $debtResult->updateDate,
                'monetary_unit_type' => $debtResult->monetaryUnitType,
            ];
        }

        $paginationResult = $result->paginationResult;
        $response = [
            'data' => $data,
            'pagination' => [
                'total_page' => $paginationResult->totalPage,
                'per_page' => $paginationResult->perPage,
                'current_page' => $paginationResult->currentPage,
            ],
        ];

        return response()->json($response, 200);
    }

    /**
     * @param Request $request request
     */
    public function getCustomerDebtDetail(Request $request)
    {
        $applicationService = new CustomerDebtHistoryListGetApplicationService(
            new DebtHistoryRepository(),
            new CustomerRepository(),
            new UserRepository()
        );

        $command = new CustomerDebtHistoryListGetCommand(
            $request->id,
            !empty($request->keyword) ? $request->keyword : null,
        );

        $result = $applicationService->handle($command);
        $data = [];
        foreach ($result->debtResults as $debtResult) {
            $data[] = [
                'debt_history_id' => $debtResult->debtHistoryId,
                'customer_id' => $debtResult->customerId,
                'customer_name' => $debtResult->customerName,
                'user_id' => $debtResult->userId,
                'user_name' => $debtResult->userName,
                'total_debt' => $debtResult->totalDebt,
                'total_payment' => $debtResult->totalPayment,
                'updated_date' => $debtResult->updateDate,
                'monetary_unit_type' => $debtResult->monetaryUnitType,
                'update_type' => $debtResult->debtHistoryUpdateType,
                'order_id' => $debtResult->orderId,
                'container_order_id' => $debtResult->containerOrderId,
                'vat_id' => $debtResult->vatId,
                'payment_id' => $debtResult->paymentId,
                'other_debt_id' => $debtResult->otherDebtId,
                'number_of_money' => $debtResult->numberOfMoney,
            ];
        }

        $paginationResult = $result->paginationResult;
        $response = [
            'data' => $data,
            'pagination' => [
                'total_page' => $paginationResult->totalPage,
                'per_page' => $paginationResult->perPage,
                'current_page' => $paginationResult->currentPage,
            ],
        ];

        return response()->json($response, 200);
    }
}
