<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\Admin\Application\CustomerDeleteApplicationService;
use App\Bundle\Admin\Application\CustomerDeleteCommand;
use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\ProductBundle\Application\CategoryGetApplicationService;
use App\Bundle\ProductBundle\Application\CategoryGetCommand;
use App\Bundle\ProductBundle\Application\CategoryListGetApplicationService;
use App\Bundle\ProductBundle\Application\CategoryListGetCommand;
use App\Bundle\ProductBundle\Application\CategoryPostApplicationService;
use App\Bundle\ProductBundle\Application\CategoryPostCommand;
use App\Bundle\ProductBundle\Application\CategoryPutCommand;
use App\Bundle\ProductBundle\Infrastructure\CategoryRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createCategory(Request $request)
    {
        $categoriesRepository = new CategoryRepository();
        $applicationService = new CategoryPostApplicationService($categoriesRepository);

        $command = new CategoryPostCommand(
            $request->category_name,
            $request->slug,
            !empty($request->parent_id) ? $request->parent_id : null,
        );

        $result = $applicationService->handle($command);
        $data = [
            $result->categoryId,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(Request $request) {
        $categoriesRepository = new CategoryRepository();
        $applicationService = new CategoryListGetApplicationService($categoriesRepository);

        $command = new CategoryListGetCommand();
        $result = $applicationService->handle($command);
        $categoryResults = $result->categoryResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($categoryResults as $category) {
            $data[] = [
                'category_id' => $category->categoryId,
                'name' => $category->name,
                'slug' => $category->slug,
                'parent_id' => $category->parentId,
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
    public function getCategory(Request $request) {
        $categoriesRepository = new CategoryRepository();
        $applicationService = new CategoryGetApplicationService($categoriesRepository);

        $command = new CategoryGetCommand($request->id);
        $category = $applicationService->handle($command);
        $data = [
            'category_id' => $category->categoryId,
            'email' => $category->name,
            'slug' => $category->slug,
            'parent_id' => $category->parentId,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function updateCategory(Request $request) {
        $categoriesRepository = new CategoryRepository();
        $applicationService = new CategoryGetApplicationService($categoriesRepository);

        $command = new CategoryPutCommand(
            $request->id,
            $request->category_name,
            $request->slug,
            (int)$request->phone,
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
