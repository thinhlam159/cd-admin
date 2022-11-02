<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Application\CustomerDeleteApplicationService;
use App\Bundle\Admin\Application\CustomerDeleteCommand;
use App\Bundle\Admin\Application\CustomerGetApplicationService;
use App\Bundle\Admin\Application\CustomerGetCommand;
use App\Bundle\Admin\Application\CustomerListGetApplicationService;
use App\Bundle\Admin\Application\CustomerListGetCommand;
use App\Bundle\Admin\Application\CustomerPostApplicationService;
use App\Bundle\Admin\Application\CustomerPostCommand;
use App\Bundle\Admin\Application\CustomerPutApplicationService;
use App\Bundle\Admin\Application\CustomerPutCommand;
use App\Bundle\Admin\Application\UserDeleteApplicationService;
use App\Bundle\Admin\Application\UserDeleteCommand;
use App\Bundle\Admin\Application\UserGetApplicationService;
use App\Bundle\Admin\Application\UserGetCommand;
use App\Bundle\Admin\Application\UserListGetApplicationService;
use App\Bundle\Admin\Application\UserListGetCommand;
use App\Bundle\Admin\Application\UserPostApplicationService;
use App\Bundle\Admin\Application\UserPostCommand;
use App\Bundle\Admin\Application\UserPutApplicationService;
use App\Bundle\Admin\Application\UserPutCommand;
use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\ProductBundle\Application\ProductGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductGetCommand;
use App\Bundle\ProductBundle\Application\ProductListGetApplicationService;
use App\Bundle\ProductBundle\Application\ProductListGetCommand;
use App\Bundle\ProductBundle\Application\ProductPostApplicationService;
use App\Bundle\ProductBundle\Application\ProductPostCommand;
use App\Bundle\ProductBundle\Application\ProductPutApplicationService;
use App\Bundle\ProductBundle\Infrastructure\CategoryRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributePriceRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductAttributeValueRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductInventoryRepository;
use App\Bundle\ProductBundle\Infrastructure\ProductRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class ProductController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createProduct(Request $request)
    {
        $applicationService = new ProductPostApplicationService(
            new ProductRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new CategoryRepository()
        );
        $base64File = $request->file;

        $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];
        $replace = substr($base64File, 0, strpos($base64File, ',')+1);
        $image = str_replace($replace, '', $base64File);
        $image = str_replace(' ', '+', $image);
        $imageName = 'images/'.Str::random(10).'.'.$extension;
        Storage::disk('public')->put($imageName, base64_decode($image));
        $url = Storage::url($imageName);
        $path = Storage::path($imageName);

// decode the base64 file
//        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));
//        $ext = pathinfo($base64File, PATHINFO_EXTENSION);

// save it to temporary dir first.
//        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
//        file_put_contents($tmpFilePath, $fileData);

// this just to help us get file info.
//        $tmpFile = new File($tmpFilePath);
//dd($tmpFile);
//        $file = new UploadedFile(
//            $tmpFile->getPathname(),
//            $tmpFile->getFilename(),
//            $tmpFile->getMimeType(),
//            0,
//            true // Mark it as test, since the file isn't from real HTTP POST.
//        );
//
//        $file->store('avatars');
//        $data = base64_decode(Storage::get('file.jpg'));
//        dd($data);
//        $image = base64_encode($base64File);
//        Storage::put('file.jpg', $image);
//        $data = base64_decode(Storage::get('file.jpg'));
//        dd($data);
//        Storage::put('file.jpg', $encoded_image);


        $isAvatar = true;

        $command = new ProductPostCommand(
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
        $data = [
            $result->productId,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(Request $request) {
        $applicationService = new ProductListGetApplicationService(
            new ProductRepository(),
            new ProductAttributeValueRepository(),
            new ProductAttributePriceRepository(),
            new ProductInventoryRepository(),
            new CategoryRepository()
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

        $command = new CustomerPutCommand(
            $request->id,
            $request->user_name,
            $request->email,
            (int)$request->phone,
            $request->status
        );
        $result = $applicationService->handle($command);

        return response()->json(['customer_id' => $result->customerId], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function deleteCustomer(Request $request) {
        $customerRepository = new CustomerRepository();
        $applicationService = new CustomerDeleteApplicationService(
            $customerRepository,
        );
        $command = new CustomerDeleteCommand($request->id);

        $result = $applicationService->handle($command);

        return response()->json(['data' => []], 200);
    }
}
