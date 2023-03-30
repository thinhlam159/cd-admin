<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\ProductBundle\Application\ContainerOrderCancelPutApplicationService;
use App\Bundle\ProductBundle\Application\ContainerOrderCancelPutCommand;
use App\Bundle\ProductBundle\Application\ContainerOrderCustomerListGetApplicationService;
use App\Bundle\ProductBundle\Application\ContainerOrderCustomerListGetCommand;
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
use App\Bundle\ProductBundle\Application\PaymentCancelPutApplicationService;
use App\Bundle\ProductBundle\Application\PaymentCancelPutCommand;
use App\Bundle\ProductBundle\Application\PaymentCustomerListGetApplicationService;
use App\Bundle\ProductBundle\Application\PaymentCustomerListGetCommand;
use App\Bundle\ProductBundle\Application\PaymentPostApplicationService;
use App\Bundle\ProductBundle\Application\PaymentPostCommand;
use App\Bundle\ProductBundle\Application\PaymentResolvedPutApplicationService;
use App\Bundle\ProductBundle\Application\PaymentResolvedPutCommand;
use App\Bundle\ProductBundle\Application\VatCancelPutApplicationService;
use App\Bundle\ProductBundle\Application\VatCancelPutCommand;
use App\Bundle\ProductBundle\Application\VatCustomerListGetApplicationService;
use App\Bundle\ProductBundle\Application\VatCustomerListGetCommand;
use App\Bundle\ProductBundle\Application\VatPostApplicationService;
use App\Bundle\ProductBundle\Application\VatPostCommand;
use App\Bundle\ProductBundle\Domain\Model\IContainerOrderRepository;
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
    public function getCustomerPayments(Request $request)
    {
        $applicationService = new PaymentCustomerListGetApplicationService(
            new PaymentRepository(),
        );

        $command = new PaymentCustomerListGetCommand(
            $request->customer_id
        );
        $result = $applicationService->handle($command);
        $paginationResult = $result->pagination;
        $paymentResults = $result->paymentResults;
        $data = [];
        foreach ($paymentResults as $paymentResult) {
            $data[] = [
                'payment_id' => $paymentResult->paymentId,
                'cost' => $paymentResult->cost,
                'monetary_unit_type' => $paymentResult->monetaryUnitType,
                'comment' => $paymentResult->comment,
                'customer_id' => $paymentResult->customerId,
                'user_id' => $paymentResult->userId,
                'date' => $paymentResult->date,
                'payment_status' => $paymentResult->paymentStatus,
            ];
        }

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
    public function updateResolvedPayment(Request $request)
    {
        $applicationService = new PaymentResolvedPutApplicationService(
            new PaymentRepository(),
            new DebtHistoryRepository()
        );

        $command = new PaymentResolvedPutCommand(
            $request->payment_id,
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
    public function cancelPayment(Request $request)
    {
        $applicationService = new PaymentCancelPutApplicationService(
            new PaymentRepository(),
            new DebtHistoryRepository()
        );
        $command = new PaymentCancelPutCommand(
            $request->payment_id,
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
    public function getCustomerContainerOrders(Request $request)
    {
        $applicationService = new ContainerOrderCustomerListGetApplicationService(
            new ContainerOrderRepository(),
        );

        $command = new ContainerOrderCustomerListGetCommand(
            $request->customer_id
        );

        $result = $applicationService->handle($command);
        $paginationResult = $result->pagination;
        $containerOrderResults = $result->containerOrderResults;
        $data = [];
        foreach ($containerOrderResults as $containerOrderResult) {
            $data[] = [
                'container_order_id' => $containerOrderResult->containerOrderId,
                'cost' => $containerOrderResult->cost,
                'monetary_unit_type' => $containerOrderResult->monetaryUnitType,
                'comment' => $containerOrderResult->comment,
                'customer_id' => $containerOrderResult->customerId,
                'user_id' => $containerOrderResult->userId,
                'date' => $containerOrderResult->date,
                'payment_status' => $containerOrderResult->paymentStatus,
            ];
        }

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
    public function cancelContainerOrder(Request $request)
    {
        $applicationService = new ContainerOrderCancelPutApplicationService(
            new ContainerOrderRepository(),
            new DebtHistoryRepository()
        );

        $command = new ContainerOrderCancelPutCommand(
            $request->container_order_id,
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
    public function getCustomerVats(Request $request)
    {
        $applicationService = new VatCustomerListGetApplicationService(
            new VatRepository(),
        );

        $command = new VatCustomerListGetCommand(
            $request->customer_id
        );
        $result = $applicationService->handle($command);
        $paginationResult = $result->pagination;
        $vatResults = $result->vatResults;
        $data = [];
        foreach ($vatResults as $vatResult) {
            $data[] = [
                'vat_id' => $vatResult->vatId,
                'cost' => $vatResult->cost,
                'monetary_unit_type' => $vatResult->monetaryUnitType,
                'comment' => $vatResult->comment,
                'customer_id' => $vatResult->customerId,
                'user_id' => $vatResult->userId,
                'date' => $vatResult->date,
                'payment_status' => $vatResult->paymentStatus,
            ];
        }

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
    public function cancelVat(Request $request)
    {
        $applicationService = new VatCancelPutApplicationService(
            new VatRepository(),
            new DebtHistoryRepository()
        );
        $command = new VatCancelPutCommand(
            $request->vat_id,
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
            $debt = [
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
            if (!is_null($debtResult->paymentId)) {
                $debt['is_payment'] = true;
            } else {
                $debt['is_payment'] = false;
            }
            $data[] = $debt;
        }

        $response = [
            'data' => $data,
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
                2 => 'Nợ phải thu',
                3 => 'Thanh toán',
                4 => 'Phải thu tăng',
                5 => 'Ghi chú',
            ]
        ];
        foreach ($result->debtResults as $key => $debtResult) {
            $key ++;
            $rest = $debtResult->totalDebt - $debtResult->totalPayment;
            $template[] = [
                0 => $key,
                1 => $debtResult->updatedDate,
                2 => $rest,
                3 => !is_null($debtResult->paymentId) ? $debtResult->numberOfMoney : '-',
                4 => is_null($debtResult->paymentId) ? $debtResult->numberOfMoney : '-',
                5 => $debtResult->comment,
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
