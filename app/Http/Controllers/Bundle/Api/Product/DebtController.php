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
            $request->customer_id,
            !empty($request->startDate) ? $request->startDate : null,
            !empty($request->endDate) ? $request->endDate : null
        );

        $result = $application->handle($command);
        $customerName = "Tên khách hàng: $result->customerName";

        $template = [
            [
                0 => "CÔNG TY TNHH SẢN XUẤT VÀ XUẤT NHẬP KHẨU HƯNG THỊNH - NH",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "CHUYÊN SẢN XUẤT CÁC LOẠI BĂNG DÍNH",
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
                0 => "ĐT: 0988.397.883 - 0987.594.704",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "STK: 100000958649 - Ngân hàng Viettinbank, CN Đông Hà Nội - Người thụ hưởng: Thạch Thị Thùy Hương",
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
                0 => null,
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "HÓA ĐƠN BÁN HÀNG",
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
                5 => "Điện thoại:",
            ],
            [
                0 => "Địa chỉ:",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
            ],
            [
                0 => "STT",
                1 => "Tên sản phẩm",
                2 => 'ĐVT',
                3 => 'Số lượng',
                4 => 'Đơn giá',
                5 => 'Thành tiền',
            ]
        ];
        foreach ($result->debtResults as $key => $debtResult) {
            $key ++;
            $template[] = [
                0 => $key,
                1 => "$debtResult->productCode $orderProduct->productAttributeValueCode$orderProduct->attributeDisplayIndex",
                2 => $debtResult->measureUnitType,
                3 => $debtResult->weight,
                4 => $debtResult->productAttributePriceStandard,
                5 => $debtResult->productOrderCost,
            ];
        }
        $footer = [
            [
                0 => "Tổng cộng",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => $result->totalCost
            ],
            [
                0 => "Số tiền bằng chữ",
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null
            ],
            [
                0 => null,
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null
            ],
            [
                0 => null,
                1 => null,
                2 => null,
                3 => null,
                4 => "Ngày tháng năm 2022",
                5 => null
            ],
            [
                0 => null,
                1 => 'Người mua hàng',
                2 => null,
                3 => null,
                4 => 'Người bán hàng',
                5 => null
            ],
        ];
        $template = array_merge($template, $footer);
    }
}
