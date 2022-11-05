<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Application\CustomerDeleteCommand;
use App\Bundle\ProductBundle\Application\ProductAttributeListGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductAttributeListGetCommand;
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
use App\Bundle\ProductBundle\Infrastructure\ProductAttributePriceRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeValueRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductInventoryRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createProduct(Request $request)
    {
        $applicationService = new ProductPostApplicationService(
            new ProductRepository(),
            new FeatureImagePathRepository(),
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
            new FeatureImagePathRepository()
        );

        $command = new ProductListGetCommand();
        $result = $applicationService->handle($command);
        $productResults = $result->productResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($productResults as $product) {
//            $productAttributeValues = [];
//            foreach ($product->productAttributeValueResults as $productAttributeValueResult) {
//                $productAttributeValues[] = [
//                    'product_attribute_value_id' => $productAttributeValueResult->productAttributeValueId,
//                    'product_attribute_name' => $productAttributeValueResult->productAttributeName,
//                    'product_attribute_value' => $productAttributeValueResult->productAttributeValue,
//                    'attribute_name' => $productAttributeValueResult->nameByAttribute,
//                    'product_inventory_count' => $productAttributeValueResult->productInventoryCount,
//                    'measure_unit' => $productAttributeValueResult->measureUnit,
//                    'price' => $productAttributeValueResult->price,
//                    'monetary_unit' => $productAttributeValueResult->monetaryUnit,
//                ];
//            }
            $data[] = [
                'product_id' => $product->productId,
                'name' => $product->name,
                'code' => $product->code,
                'description' => $product->description,
                'category_id' => $product->categoryId,
                'category_name' => $product->categoryName,
                'image_path' => url($product->imagePath),
//                'product_attribute_values' => $productAttributeValues
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
            new CategoryRepository()
        );

        $command = new ProductGetCommand($request->id);
        $product = $applicationService->handle($command);
        $productAttributeValues = [];
        foreach ($product->productAttributeValueResults as $productAttributeValueResult) {
            $productAttributeValues[] = [
                'product_attribute_value_id' => $productAttributeValueResult->productAttributeValueId,
                'product_attribute_name' => $productAttributeValueResult->productAttributeName,
                'product_attribute_value' => $productAttributeValueResult->productAttributeValue,
                'attribute_name' => $productAttributeValueResult->nameByAttribute,
                'product_inventory_count' => $productAttributeValueResult->productInventoryCount,
                'measure_unit' => $productAttributeValueResult->measureUnit,
                'price' => $productAttributeValueResult->price,
                'monetary_unit' => $productAttributeValueResult->monetaryUnit,
            ];
        }
        $data[] = [
            'product_id' => $product->productId,
            'name' => $product->name,
            'code' => $product->code,
            'description' => $product->description,
            'category_id' => $product->categoryId,
            'category_name' => $product->categoryName,
            'product_attribute_values' => $productAttributeValues
        ];

        return response()->json($data, 200);
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
    public function getProductAttribute(Request $request) {
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
}
