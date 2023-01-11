<?php

namespace App\Http\Controllers\Bundle\Api\Admin;

use App\Bundle\Admin\Application\DealerListGetApplicationService;
use App\Bundle\Admin\Application\DealerListGetCommand;
use App\Bundle\Admin\Infrastructure\DealerRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;

class DealerController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDealers(Request $request) {
        $applicationService = new DealerListGetApplicationService(
            new DealerRepository(),
        );
        $command = new DealerListGetCommand();
        $result = $applicationService->handle($command);
        $dealerManageResults = $result->dealerResults;
        $paginationResult = $result->paginationResult;
        $data = [];

        foreach ($dealerManageResults as $dealer) {
            $data[] = [
                'dealer_id' => $dealer->customerId,
                'dealer_name' => $dealer->customerName,
                'dealer_email' => $dealer->email,
                'phone' => $dealer->phone,
                'status' => $dealer->isActive,
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
}
