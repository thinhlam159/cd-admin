<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Application\CustomerDeleteCommand;
use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Application\MeasureUnitListGetApplicationService;
use App\Bundle\ProductBundle\Application\MeasureUnitListGetCommand;
use App\Bundle\ProductBundle\Application\ProductAttributeListGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductAttributeListGetCommand;
use App\Bundle\ProductBundle\Application\ProductAttributePriceCommand;
use App\Bundle\ProductBundle\Application\ProductAttributePriceListPutApplicationService;
use App\Bundle\ProductBundle\Application\ProductAttributePriceListPutCommand;
use App\Bundle\ProductBundle\Application\ProductAttributeValueListGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductAttributeValueListGetCommand;
use App\Bundle\ProductBundle\Application\ProductAttributeValuePostApplicationService;
use App\Bundle\ProductBundle\Application\ProductAttributeValuePostCommand;
use App\Bundle\ProductBundle\Application\ProductGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductGetCommand;
use App\Bundle\ProductBundle\Application\ProductListGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductListGetCommand;
use App\Bundle\ProductBundle\Application\ProductPostApplicationService;
use App\Bundle\ProductBundle\Application\ProductPostCommand;
use App\Bundle\ProductBundle\Application\ProductPutApplicationService;
use App\Bundle\ProductBundle\Application\ProductPutCommand;
use App\Bundle\ProductBundle\Infrastructure\CategoryRepository;
use App\Bundle\ProductBundle\Infrastructure\FeatureImagePathRepository;
use App\Bundle\ProductBundle\Infrastructure\MeasureUnitRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributePriceRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeValueRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductInventoryRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class OrderController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createOrder(Request $request)
    {
        $applicationService = new ProductPostApplicationService(
            new OrderRepository(),
            new CustomerRepository(),
            new UserRepository(),
            new ProductRepository(),
        );

        $file = $request->file('file');
        if (!$file) {
            throw new InvalidArgumentException();
        }
        $file->hashName();
        $path = Storage::put('/public/'. Auth::id(), $file);
        $url = Storage::url($path);

        $isAvatar = true;

        $command = new ProductPostCommand(
            $request->name,
            $request->code,
            $request->description,
            $request->category_id,
            $url,
            $isAvatar
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->productId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(Request $request) {
        $applicationService = new ProductListGetApplicationService(
            new ProductRepository(),
            new CategoryRepository(),
            new FeatureImagePathRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new ProductAttributeRepository(),
            new MeasureUnitRepository(),
        );

        $command = new ProductListGetCommand();
        $result = $applicationService->handle($command);
        $productResults = $result->productResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($productResults as $product) {
            $productAttributeValues = [];
            foreach ($product->productAttributeValueResults as $productAttributeValueResult) {
                $productAttributeValues[] = [
                    'product_attribute_value_id' => $productAttributeValueResult->productAttributeValueId,
                    'code' => $productAttributeValueResult->code,
                    'product_attribute_value' => $productAttributeValueResult->productAttributeValue,
                    'attribute_name' => $productAttributeValueResult->productAttributeName,
                    'count' => $productAttributeValueResult->productInventoryCount,
                    'measure_unit_name' => $productAttributeValueResult->measureUnit,
                    'price' => $productAttributeValueResult->price,
                    'notice_price_type' => $productAttributeValueResult->noticePriceType,
                    'monetary_unit_name' => $productAttributeValueResult->monetaryUnit,
                ];
            }
            $data[] = [
                'product_id' => $product->productId,
                'name' => $product->name,
                'code' => $product->code,
                'description' => $product->description,
                'category_id' => $product->categoryId,
                'category_name' => $product->categoryName,
                'image_path' => url($product->imagePath),
                'product_attribute_values' => $productAttributeValues
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
    public function getProduct(Request $request) {
        $applicationService = new ProductGetApplicationService(
            new ProductRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new CategoryRepository(),
            new ProductAttributeRepository(),
            new MeasureUnitRepository(),
        );

        $command = new ProductGetCommand($request->id);
        $product = $applicationService->handle($command);
        $productAttributeValues = [];
        foreach ($product->productAttributeValueResults as $productAttributeValueResult) {
            $productAttributeValues[] = [
                'product_attribute_value_id' => $productAttributeValueResult->productAttributeValueId,
                'product_attribute_name' => $productAttributeValueResult->productAttributeName,
                'product_attribute_value' => $productAttributeValueResult->productAttributeValue,
                'attribute_name' => $productAttributeValueResult->code,
                'product_inventory_count' => $productAttributeValueResult->productInventoryCount,
                'measure_unit' => $productAttributeValueResult->measureUnit,
                'price' => $productAttributeValueResult->price,
                'monetary_unit' => $productAttributeValueResult->monetaryUnit,
            ];
        }
        $data = [
            'product_id' => $product->productId,
            'name' => $product->name,
            'code' => $product->code,
            'description' => $product->description,
            'category_id' => $product->categoryId,
            'category_name' => $product->categoryName,
            'product_attribute_values' => $productAttributeValues
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function updateProduct(Request $request) {
        $applicationService = new ProductPutApplicationService(
            new ProductRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new CategoryRepository()
        );

        //handle image
        $base64File = $request->file;
        $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];
        $replace = substr($base64File, 0, strpos($base64File, ',')+1);
        $image = str_replace($replace, '', $base64File);
        $image = str_replace(' ', '+', $image);
        $imageName = 'images/'.Str::random(10).'.'.$extension;
        Storage::disk('public')->put($imageName, base64_decode($image));
        $url = Storage::url($imageName);
        $path = Storage::path($imageName);

        $isAvatar = true;
        $command = new ProductPutCommand(
            $request->id,
            $request->name,
            $request->code,
            $request->description,
            $request->price,
            $request->monetary_unit,
            $request->category_id,
            $request->measure_unit_id,
            $request->product_attribute_id,
            $request->product_attribute_value,
            $request->product_attribute_code,
            $path,
            $isAvatar
        );
        $result = $applicationService->handle($command);

        return response()->json(['product_id' => $result->productId], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function deleteProduct(Request $request) {
        $applicationService = new ProductDeleteApplicationService(
            new ProductRepository(),
        );
        $command = new CustomerDeleteCommand($request->id);

        $result = $applicationService->handle($command);

        return response()->json(['data' => []], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function getProductAttributes(Request $request) {
        $applicationService = new ProductAttributeListGetApplicationService(
            new ProductAttributeRepository()
        );

        $command = new ProductAttributeListGetCommand();
        $result = $applicationService->handle($command);

        $data = [];
        foreach ($result->productAttributeResults as $productAttributeResult) {
            $data[] = [
                'id' => $productAttributeResult->productAttributeId,
                'name' => $productAttributeResult->name,
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    public function createAttributeValueForProduct(Request $request)
    {
        $applicationService = new ProductAttributeValuePostApplicationService(
            new ProductAttributeValueRepository(),
            new FeatureImagePathRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository()
        );
        $file = $request->file('file');
        if (!$file) {
            throw new InvalidArgumentException();
        }
        $file->hashName();
        $path = Storage::put('/public/'. Auth::id(), $file);
        $url = Storage::url($path);

        $command = new ProductAttributeValuePostCommand(
            $request->product_id,
            $request->product_attribute_id,
            $request->measure_unit_id,
            $request->value,
            $request->code,
            $url,
            (int)$request->price,
            (int)$request->count,
        );

        $result = $applicationService->handle($command);
        $data = [
            'id' => $result->productAttributeValueId,
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function getMeasureUnit(Request $request) {
        $applicationService = new MeasureUnitListGetApplicationService(
            new MeasureUnitRepository()
        );

        $command = new MeasureUnitListGetCommand();
        $result = $applicationService->handle($command);

        $data = [];
        foreach ($result->measureUnitResults as $measureUnitResult) {
            $data[] = [
                'id' => $measureUnitResult->measureUnitId,
                'name' => $measureUnitResult->name,
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductAttributeValues(Request $request) {
        $applicationService = new ProductAttributeValueListGetApplicationService(
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new ProductAttributeRepository(),
        );

        $command = new ProductAttributeValueListGetCommand(
            $request->productId ?? null
        );
        $result = $applicationService->handle($command);

        $data = [];
        foreach ($result->productAttributeValueResults as $productAttributeValueResult) {
            $data[] = [
                'id' => $productAttributeValueResult->productAttributeValueId,
                'product_id' => $productAttributeValueResult->productId,
                'product_attribute_name' => $productAttributeValueResult->productAttributeName,
                'product_attribute_value' => $productAttributeValueResult->productAttributeValue,
                'code' => $productAttributeValueResult->code,
                'measure_unit' => $productAttributeValueResult->measureUnit,
                'inventory_count' => $productAttributeValueResult->productInventoryCount,
                'price' => $productAttributeValueResult->price,
                'monetary' => $productAttributeValueResult->monetaryUnit,
                'notice_price_type' => $productAttributeValueResult->noticePriceType,
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidArgumentException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function updateProductAttributeValuePrice(Request $request)
    {
        $applicationService = new ProductAttributePriceListPutApplicationService(
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new ProductAttributeRepository(),
        );
        $productAttributeValuePriceCommands = [];
        $productAttributeValuePrices = !empty($request->product_attribute_value_price) ? $request->product_attribute_value_price : [];
        foreach ($productAttributeValuePrices as $productAttributeValuePrice) {
            $productAttributeValuePriceCommands[] = new ProductAttributePriceCommand(
                $productAttributeValuePrice->product_attribute_price_id,
                $productAttributeValuePrice->product_attribute_value_id,
                $productAttributeValuePrice->price,
                $productAttributeValuePrice->notice_price_type,
            );
        }
        $command = new ProductAttributePriceListPutCommand(
            $productAttributeValuePriceCommands
        );
        $result = $applicationService->handle($command);

        return response()->json(['data' => []], 200);
    }
}
