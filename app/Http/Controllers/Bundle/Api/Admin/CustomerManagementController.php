<?php

namespace App\Http\Controllers\Bundle\Api\Admin;

use App\Bundle\Admin\Application\CustomerAllListGetApplicationService;
use App\Bundle\Admin\Application\CustomerAllListGetCommand;
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
use App\Bundle\Admin\Infrastructure\CustomerRepository;
use App\Bundle\ProductBundle\Infrastructure\DebtHistoryRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerManagementController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createCustomer(Request $request)
    {
        $applicationService = new CustomerPostApplicationService(
            new CustomerRepository(),
            new DebtHistoryRepository()
        );

        $command = new CustomerPostCommand(
            $request->customer_name,
            $request->email,
            Hash::make($request->password),
            $request->address,
            $request->phone,
            $request->status
        );

        $result = $applicationService->handle($command);
        $data = [
            $result->customerId,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers(Request $request) {
        $customerRepository = new CustomerRepository();
        $applicationService = new CustomerListGetApplicationService(
            $customerRepository,
        );

        $command = new CustomerListGetCommand(
            !empty($request->keyword) ? $request->keyword : null
        );
        $result = $applicationService->handle($command);
        $customerManageResults = $result->customerResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($customerManageResults as $customer) {
            $data[] = [
                'customer_id' => $customer->customerId,
                'customer_name' => $customer->customerName,
                'customer_email' => $customer->email,
                'address' => $customer->address,
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
     */
    public function getCustomerAll(Request $request) {
        $customerRepository = new CustomerRepository();
        $applicationService = new CustomerAllListGetApplicationService(
            $customerRepository,
        );

        $command = new CustomerAllListGetCommand(
            !empty($request->keyword) ? $request->keyword : null
        );
        $result = $applicationService->handle($command);
        $customerManageResults = $result->customerResults;

        $data = [];
        foreach ($customerManageResults as $customer) {
            $data[] = [
                'customer_id' => $customer->customerId,
                'customer_name' => $customer->customerName,
            ];
        }

        return response()->json(['data' => $data,], 200);
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
            'address' => $customer->address,
            'phone' => $customer->phone,
            'status' => $customer->isActive,
        ];

        return response()->json(['data' => $data], 200);
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
            $request->email,
            $request->customer_name,
            $request->address,
            $request->phone,
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
