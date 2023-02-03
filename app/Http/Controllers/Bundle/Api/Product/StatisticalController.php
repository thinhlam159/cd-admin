<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\ProductBundle\Application\StatisticalDebtListGetApplicationService;
use App\Bundle\ProductBundle\Application\StatisticalDebtListGetCommand;
use App\Bundle\ProductBundle\Application\StatisticalPeriodRevenueGetApplicationService;
use App\Bundle\ProductBundle\Application\StatisticalPeriodRevenueGetCommand;
use App\Bundle\ProductBundle\Application\StatisticalProductSaleListGetApplicationService;
use App\Bundle\ProductBundle\Application\StatisticalProductSaleListGetCommand;
use App\Bundle\ProductBundle\Infrastructure\CategoryRepository;
use App\Bundle\ProductBundle\Infrastructure\DebtHistoryRepository;
use App\Bundle\ProductBundle\Infrastructure\OrderRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeValueRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
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
    public function getPeriodRevenues(Request $request)
    {
        $applicationService = new StatisticalPeriodRevenueGetApplicationService(
            new DebtHistoryRepository()
        );

        $command = new StatisticalPeriodRevenueGetCommand(
            !empty($request->start_date) ? $request->start_date : null,
            !empty($request->end_date) ? $request->end_date : null
        );

        $result = $applicationService->handle($command);
        $data = [];
        $data['total'] = $result->total;

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request request
     */
    public function getStatisticalProductSaleByCategory(Request $request)
    {
        $applicationService = new StatisticalProductSaleListGetApplicationService(
            new CategoryRepository(),
            new OrderRepository(),
            new CustomerRepository(),
            new ProductRepository(),
            new ProductAttributeValueRepository(),
        );

        $command = new StatisticalProductSaleListGetCommand(
            $request->category_id,
            !empty($request->product_attribute_value_id) ? $request->product_attribute_value_id : null,
            !empty($request->start_date) ? $request->start_date : null,
            !empty($request->end_date) ? $request->end_date : null
        );

        $result = $applicationService->handle($command);
        $data = [];
        foreach ($result->statisticalProductSaleResults as $statisticalProductSaleResult) {
            $data[] = [
                'product_id' => $statisticalProductSaleResult->productId,
                'name' => $statisticalProductSaleResult->productName,
                'code' => $statisticalProductSaleResult->productCode,
                'count' => $statisticalProductSaleResult->count,
            ];
        }

        return response()->json(['data' => $data], 200);
    }
}
