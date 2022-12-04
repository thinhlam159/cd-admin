<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\ProductBundle\Application\DeliveryStatusPutApplicationService;
use App\Bundle\ProductBundle\Application\DeliveryStatusPutCommand;
use App\Bundle\ProductBundle\Application\ImportGoodPostApplicationService;
use App\Bundle\ProductBundle\Application\ImportGoodPostCommand;
use App\Bundle\ProductBundle\Application\ImportGoodProductCommand;
use App\Bundle\ProductBundle\Application\OrderCancelPostApplicationService;
use App\Bundle\ProductBundle\Application\OrderCancelPostCommand;
use App\Bundle\ProductBundle\Application\OrderExportPostApplicationService;
use App\Bundle\ProductBundle\Application\OrderExportPostCommand;
use App\Bundle\ProductBundle\Application\OrderGetApplicationService;
use App\Bundle\ProductBundle\Application\OrderGetCommand;
use App\Bundle\ProductBundle\Application\OrderListGetApplicationService;
use App\Bundle\ProductBundle\Application\OrderListGetCommand;
use App\Bundle\ProductBundle\Application\OrderPostApplicationService;
use App\Bundle\ProductBundle\Application\OrderPostCommand;
use App\Bundle\ProductBundle\Application\OrderProductCommand;
use App\Bundle\ProductBundle\Application\RestoreImportGoodPutApplicationService;
use App\Bundle\ProductBundle\Application\RestoreImportGoodPutCommand;
use App\Bundle\ProductBundle\Infrastructure\ImportGoodRepository;
use App\Bundle\ProductBundle\Infrastructure\OrderRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributePriceRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeValueRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductInventoryRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
use App\Exports\OrderExport;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use App\Imports\OrderImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

final class OrderController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createOrder(Request $request)
    {
        $applicationService = new OrderPostApplicationService(
            new OrderRepository(),
            new CustomerRepository(),
            new UserRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
        );

        $orderProducts = $request->order_products;
        $orderProductCommands = [];
        foreach ($orderProducts as $orderProduct) {
            $orderProductCommands[] = new OrderProductCommand(
                $orderProduct['product_id'],
                $orderProduct['product_attribute_value_id'],
                (int)$orderProduct['attribute_display_index'],
                $orderProduct['product_attribute_price_id'],
                (int)$orderProduct['count'],
                $orderProduct['measure_unit_type'],
                (int)$orderProduct['weight'],
            );
        }

        $command = new OrderPostCommand(
            $request->customer_id,
            Auth::id(),
            $orderProductCommands
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->orderId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders(Request $request) {
        $applicationService = new OrderListGetApplicationService(
            new OrderRepository(),
            new CustomerRepository(),
            new UserRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductRepository(),
        );

        $command = new OrderListGetCommand();
        $result = $applicationService->handle($command);
        $orderResults = $result->orderResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($orderResults as $orderResult) {
            $orderProducts = [];
            foreach ($orderResult->orderProductResults as $orderProductResult) {
                $orderProducts[] = [
                    'order_product_id' => $orderProductResult->orderProductId,
                    'order_id' => $orderProductResult->orderId,
                    'product_id' => $orderProductResult->productId,
                    'product_attribute_value_id' => $orderProductResult->productAttributeValueId,
                    'product_attribute_price_id' => $orderProductResult->productAttributePriceId,
                    'measure_unit_type' => $orderProductResult->measureUnitType,
                    'weight' => $orderProductResult->weight,
                    'attribute_display_index' => $orderProductResult->count,
                    'notice_price_type' => $orderProductResult->noticePriceType,
                    'price' => $orderProductResult->price,
                    'cost' => $orderProductResult->cost,
                    'count' => $orderProductResult->count,
                    'product_attribute_value_code' => $orderProductResult->productAttributeValueCode,
                    'product_name' => $orderProductResult->productName,
                    'product_code' => $orderProductResult->productCode,
                ];
            }
            $data[] = [
                'order_id' => $orderResult->orderId,
                'customer_id' => $orderResult->customerId,
                'user_id' => $orderResult->userId,
                'delivery_status' => $orderResult->deliveryStatus,
                'payment_status' => $orderResult->paymentStatus,
                'updated_at' => $orderResult->updatedAt,
                'customer_name' => $orderResult->customerName,
                'user_name' => $orderResult->userName,
                'order_products' => $orderProducts,
                'total_cost' => $orderResult->totalCost
            ];
        }
        $response = [
            'data' => $data,
            'pagination' => [
                'total' => $paginationResult->totalPage,
                'per_page' => $paginationResult->perPage,
                'current_page' => $paginationResult->currentPage,
            ],
        ];

        return response()->json($response, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     */
    public function getOrder(Request $request) {
        $applicationService = new OrderGetApplicationService(
            new OrderRepository(),
            new CustomerRepository(),
            new UserRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductRepository(),
        );

        $command = new OrderGetCommand(
            $request->id
        );
        $result = $applicationService->handle($command);

            $orderProducts = [];
            foreach ($result->orderProductResults as $orderProductResult) {
                $orderProducts[] = [
                    'order_product_id' => $orderProductResult->orderProductId,
                    'order_id' => $orderProductResult->orderId,
                    'product_id' => $orderProductResult->productId,
                    'product_attribute_value_id' => $orderProductResult->productAttributeValueId,
                    'product_attribute_price_id' => $orderProductResult->productAttributePriceId,
                    'measure_unit_type' => $orderProductResult->measureUnitType,
                    'weight' => $orderProductResult->weight,
                    'attribute_display_index' => $orderProductResult->count,
                    'notice_price_type' => $orderProductResult->noticePriceType,
                    'price' => $orderProductResult->price,
                    'cost' => $orderProductResult->cost,
                    'count' => $orderProductResult->count,
                    'product_attribute_value_code' => $orderProductResult->productAttributeValueCode,
                    'product_name' => $orderProductResult->productName,
                    'product_code' => $orderProductResult->productCode,
                ];
            }
            $data = [
                'order_id' => $result->orderId,
                'customer_id' => $result->customerId,
                'customer_name' => $result->customerName,
                'user_id' => $result->userId,
                'user_name' => $result->userName,
                'delivery_status' => $result->deliveryStatus,
                'payment_status' => $result->paymentStatus,
                'update_at' => $result->updatedAt,
                'order_products' => $orderProducts,
            ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     */
    public function updateDeliveryStatus(Request $request) {
        $applicationService = new DeliveryStatusPutApplicationService(
            new OrderRepository(),
        );

        $command = new DeliveryStatusPutCommand(
            $request->order_id,
            $request->delivery_status,
        );
        $result = $applicationService->handle($command);

        $data[] = [
            'order_id' => $result->orderId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     */
    public function updatePaymentStatus(Request $request) {
        $applicationService = new DeliveryStatusPutApplicationService(
            new OrderRepository(),
        );

        $command = new DeliveryStatusPutCommand(
            $request->order_id,
            $request->delivery_status,
        );
        $result = $applicationService->handle($command);

        $data[] = [
            'order_id' => $result->orderId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     */
    public function cancelOrder(Request $request) {
        $applicationService = new OrderCancelPostApplicationService(
            new OrderRepository(),
        );

        $command = new OrderCancelPostCommand(
            $request->order_id,
        );
        $result = $applicationService->handle($command);

        $data[] = [
            'order_id' => $result->orderId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     */
    public function createImportGood(Request $request) {
        $applicationService = new ImportGoodPostApplicationService(
            new ImportGoodRepository(),
            new ProductInventoryRepository()
        );

        $importGoodProducts = $request->import_good_products;
        $importGoodProductCommands = [];
        foreach ($importGoodProducts as $importGoodProduct) {
            $importGoodProductCommands[] = new ImportGoodProductCommand(
                $importGoodProduct['product_id'],
                $importGoodProduct['product_attribute_value_id'],
                $importGoodProduct['price'],
                $importGoodProduct['monetary_unit_type'],
                $importGoodProduct['count'],
                $importGoodProduct['measure_unit_type'],
            );
        }

        $command = new ImportGoodPostCommand(
            $request->dealer_id,
            Auth::id(),
            $importGoodProductCommands,
        );
        $result = $applicationService->handle($command);

        $data[] = [
            'import_good_id' => $result->importGood,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\InvalidArgumentException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function restoreImportGood(Request $request) {
        $applicationService = new RestoreImportGoodPutApplicationService(
            new ImportGoodRepository(),
            new ProductInventoryRepository()
        );

        $command = new RestoreImportGoodPutCommand($request->import_good_id);
        $result = $applicationService->handle($command);

        $data[] = [
            'import_good_id' => $result->importGood,
        ];

        return response()->json(['data' => $data], 200);
    }

    public function exportOrder(Request $request)
    {
        $applicationService = new OrderExportPostApplicationService(
            new OrderRepository(),
            new CustomerRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductRepository()
        );

        $command = new OrderExportPostCommand(
            $request->order_id,
        );

        $result = $applicationService->handle($command);
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
        foreach ($result->orderProductExportResults as $key => $orderProduct) {
            $key ++;
            $template[] = [
                0 => $key,
                1 => "$orderProduct->productCode $orderProduct->productAttributeValueCode$orderProduct->attributeDisplayIndex",
                2 => $orderProduct->measureUnitType,
                3 => $orderProduct->weight,
                4 => $orderProduct->productAttributePriceStandard,
                5 => $orderProduct->productOrderCost,
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
        $orderExport = new OrderExport($template);
        return Excel::download($orderExport, "dfsf.xlsx");
    }
}
