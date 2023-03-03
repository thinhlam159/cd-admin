<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\ProductBundle\Application\ContainerOrderPostApplicationService;
use App\Bundle\ProductBundle\Application\ContainerOrderPostCommand;
use App\Bundle\ProductBundle\Application\CustomerCurrentDebtGetApplicationService;
use App\Bundle\ProductBundle\Application\CustomerCurrentDebtGetCommand;
use App\Bundle\ProductBundle\Application\CustomerDebtHistoryListGetApplicationService;
use App\Bundle\ProductBundle\Application\CustomerDebtHistoryListGetCommand;
use App\Bundle\ProductBundle\Application\DebtListGetApplicationService;
use App\Bundle\ProductBundle\Application\DebtListGetCommand;
use App\Bundle\ProductBundle\Application\DebtsCustomerExcelExportPostApplicationService;
use App\Bundle\ProductBundle\Application\DebtsCustomerExcelExportPostCommand;
use App\Bundle\ProductBundle\Application\PaymentPostApplicationService;
use App\Bundle\ProductBundle\Application\PaymentPostCommand;
use App\Bundle\ProductBundle\Application\VatPostApplicationService;
use App\Bundle\ProductBundle\Application\VatPostCommand;
use App\Bundle\ProductBundle\Infrastructure\ContainerOrderRepository;
use App\Bundle\ProductBundle\Infrastructure\DebtHistoryRepository;
use App\Bundle\ProductBundle\Infrastructure\PaymentRepository;
use App\Bundle\ProductBundle\Infrastructure\VatRepository;
use App\Exports\DebtCustomerExport;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
            !empty($request->order) ? $request->order : null,
            !empty($request->sort) ? $request->sort : null,
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
                'updated_date' => $debtResult->updatedDate,
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
                'updated_date' => $debtResult->updatedDate,
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
    public function getCustomerCurrentDebtDetail(Request $request)
    {
        $applicationService = new CustomerCurrentDebtGetApplicationService(
            new DebtHistoryRepository(),
            new CustomerRepository(),
            new UserRepository()
        );

        $command = new CustomerCurrentDebtGetCommand($request->id);

        $result = $applicationService->handle($command);
        $data = [
            'debt_history_id' => $result->debtHistoryId,
            'customer_id' => $result->customerId,
            'customer_name' => $result->customerName,
            'total_debt' => $result->totalDebt,
            'total_payment' => $result->totalPayment,
            'rest_debt' => $result->restDebt,
            'updated_date' => $result->updateDate,
            'monetary_unit_type' => $result->monetaryUnitType,
            'update_type' => $result->debtHistoryUpdateType,
            'order_id' => $result->orderId,
            'container_order_id' => $result->containerOrderId,
            'vat_id' => $result->vatId,
            'payment_id' => $result->paymentId,
            'other_debt_id' => $result->otherDebtId,
            'number_of_money' => $result->numberOfMoney,
        ];

        return response()->json(['data' => $data], 200);
    }

    public function exportCustomerDebtsExcel(Request $request)
    {
        $application = new DebtsCustomerExcelExportPostApplicationService(
            new DebtHistoryRepository(),
            new CustomerRepository(),
            new UserRepository()
        );

        $command = new DebtsCustomerExcelExportPostCommand(
            $request->id,
            !empty($request->startDate) ? $request->startDate : null,
            !empty($request->endDate) ? $request->endDate : null
        );

        $result = $application->handle($command);
        $name = $result->customerExcelExportResult->customerName;
        $totalDebt = $result->customerExcelExportResult->totalDebt;
        $totalPayment = $result->customerExcelExportResult->totalPayment;
        $restDebt = $result->customerExcelExportResult->restDebt;
        $customerName = "Tên khách hàng: $name";
        $taxNumber = '';
        $countDebtResults = count($result->debtResults);

        $template = [
            [
                0 => "CÔNG TY TNHH SẢN XUẤT VÀ THƯƠNG MẠI NAM HƯƠNG",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null,
                7 => null,
                8 => null,
                9 => null,
            ],
            [
                0 => "MST: ",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "Địa chỉ: Số 145, Đường Đình Xuyên, Xã Đình Xuyên, Huyện Gia Lâm, TP. Hà Nội",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "BẢNG THEO DÕI CÔNG NỢ",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => $customerName,
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => null,
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "STT",
                1 => "Ngày tháng",
                2 => 'Tổng công nợ',
                3 => 'Tổng thanh toán',
                4 => 'Nợ phải thu',
                5 => 'Thanh toán',
                6 => 'Đơn lẻ',
                7 => 'container',
                8 => 'VAT',
                9 => 'Ghi chú',
            ]
        ];
        foreach ($result->debtResults as $key => $debtResult) {
            $key ++;
            $rest = $debtResult->totalDebt - $debtResult->totalPayment;
            $template[] = [
                0 => $key,
                1 => $debtResult->updatedDate,
                2 => $debtResult->totalDebt,
                3 => $debtResult->totalPayment,
                4 => $rest,
                5 => !is_null($debtResult->paymentId) ? $debtResult->numberOfMoney : null,
                6 => !is_null($debtResult->orderId) ? $debtResult->numberOfMoney : null,
                7 => !is_null($debtResult->containerOrderId) ? $debtResult->numberOfMoney : null,
                8 => !is_null($debtResult->vatId) ? $debtResult->numberOfMoney : null,
                9 => $debtResult->comment,
            ];
        }
        $footer = [
            [
                0 => null,
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "TỔNG CÔNG NỢ",
                1 => null,
                2 => null,
                3 => null,
                4 => $totalDebt,
                5 => null,
            ],
            [
                0 => "ĐÃ TRẢ",
                1 => null,
                2 => null,
                3 => null,
                4 => $totalPayment,
                5 => null,
            ],
            [
                0 => "CÒN LẠI",
                1 => null,
                2 => null,
                3 => null,
                4 => $restDebt,
                5 => null,
            ],
        ];
        $template = array_merge($template, $footer);
        $orderExport = new DebtCustomerExport($template, $countDebtResults);
        return Excel::download($orderExport, "users.xlsx");
    }
}
