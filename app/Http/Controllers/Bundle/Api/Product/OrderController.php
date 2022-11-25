<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\ProductBundle\Application\DeliveryStatusPutApplicationService;
use App\Bundle\ProductBundle\Application\DeliveryStatusPutCommand;
use App\Bundle\ProductBundle\Application\ImportGoodPostCommand;
use App\Bundle\ProductBundle\Application\ImportGoodProductCommand;
use App\Bundle\ProductBundle\Application\ImportGoodPostApplicationService;
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
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeValueRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductInventoryRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
use App\Exports\OrderExport;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
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
            new ProductAttributeValueRepository(),
            new ProductInventoryRepository(),
        );

        $orderProducts = $request->order_products;
        $orderProductCommands = [];
        foreach ($orderProducts as $orderProduct) {
            $orderProductCommands[] = new OrderProductCommand(
                $orderProduct['product_id'],
                $orderProduct['product_attribute_value_id'],
                $orderProduct['product_attribute_price_id'],
                $orderProduct['count'],
                $orderProduct['measure_unit_type'],
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
            new ProductInventoryRepository(),
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
                    'count' => $orderProductResult->count,
                ];
            }
            $data[] = [
                'order_id' => $orderResult->orderId,
                'customer_id' => $orderResult->customerId,
                'user_id' => $orderResult->userId,
                'delivery_status' => $orderResult->deliveryStatus,
                'payment_status' => $orderResult->paymentStatus,
                'order_products' => $orderProducts,
                'update_at' => $orderResult->updateAt,
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
            new ProductInventoryRepository(),
        );

        $command = new OrderGetCommand(
            $request->order_id
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
                    'count' => $orderProductResult->count,
                ];
            }
            $data[] = [
                'order_id' => $result->orderId,
                'customer_id' => $result->customerId,
                'user_id' => $result->userId,
                'delivery_status' => $result->deliveryStatus,
                'payment_status' => $result->paymentStatus,
                'order_products' => $orderProducts,
                'update_at' => $result->updateAt,
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

    public function exportOrder(Request $request, Excel $excel)
    {
        $applicationService = new OrderExportPostApplicationService(
            new OrderRepository(),
            new CustomerRepository(),
            new UserRepository(),
            new ProductAttributeValueRepository(),
            new ProductInventoryRepository(),
            new ProductRepository(),
        );

        $command = new OrderExportPostCommand(
            $request->order_id,
        );

        $result = $applicationService->handle($command);
        $customerName = $result->customerName;
        $createdAt = $result->createdAt;

        $orderExport = new OrderExport([$result]);
        return $excel->download($orderExport, "$customerName-$createdAt.xlsx");
    }
}
