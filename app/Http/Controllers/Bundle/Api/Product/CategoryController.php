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
use App\Bundle\ProductBundle\Application\CategoryPostApplicationService;
use App\Bundle\ProductBundle\Application\CategoryPostCommand;
use App\Bundle\ProductBundle\Infrastructure\CategoryRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Support\Facades\Hash;
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
    public function getCategory(Request $request) {
        $categoriesRepository = new CategoryRepository();
        $applicationService = new CategoryPostApplicationService($categoriesRepository);

        $command = new CustomerListGetCommand();
        $result = $applicationService->handle($command);
        $customerManageResults = $result->customerResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($customerManageResults as $customer) {
            $data[] = [
                'user_id' => $customer->customerId,
                'user_name' => $customer->customerName,
                'user_email' => $customer->email,
                'phone' => $customer->phone,
                'status' => $customer->isActive,
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
    public function getCustomer(Request $request) {
        $customerRepository = new CustomerRepository();
        $applicationService = new CustomerGetApplicationService(
            $customerRepository,
        );

        $command = new CustomerGetCommand($request->id);
        $customer = $applicationService->handle($command);
        $data = [
            'customer_id' => $customer->customerId,
            'email' => $customer->email,
            'customer_name' => $customer->customerName,
            'phone' => $customer->phone,
            'status' => $customer->isActive,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function updateCustomer(Request $request) {
        $customerRepository = new CustomerRepository();
        $applicationService = new CustomerPutApplicationService(
            $customerRepository,
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
