<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\ProductBundle\Application\StatisticalDebtListGetApplicationService;
use App\Bundle\ProductBundle\Application\StatisticalDebtListGetCommand;
use App\Bundle\ProductBundle\Infrastructure\DebtHistoryRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;

class StatisticalController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function getRevenues(Request $request)
    {
        $applicationService = new StatisticalDebtListGetApplicationService(
            new DebtHistoryRepository()
        );

        $command = new StatisticalDebtListGetCommand(
            !empty($request->category_ids) ? $request->category_ids : [],
            !empty($request->date) ? $request->date : null,
            !empty($request->start_date) ? $request->start_date : null,
            !empty($request->end_date) ? $request->end_date : null
        );

        $result = $applicationService->handle($command);
        $data = [];
        $totalNumberOfMoney = 0;
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
                'comment' => $debtResult->comment,
            ];
            $totalNumberOfMoney += $debtResult->numberOfMoney;
        }
        $response = [
            'data' => $data,
            'total' => $totalNumberOfMoney,
        ];

        return response()->json($response, 200);
    }

    /**
     * @param Request $request request
     */
    public function getProductSaleByCategory(Request $request)
    {
        $applicationService = new StatisticalDebtListGetApplicationService(
            new DebtHistoryRepository()
        );

        $command = new StatisticalDebtListGetCommand(
            $request->category_name,
            $request->slug,
            !empty($request->parent_id) ? $request->parent_id : null,
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
                'comment' => $debtResult->comment,
            ];
        }

        return response()->json(['data' => $data], 200);
    }
}
