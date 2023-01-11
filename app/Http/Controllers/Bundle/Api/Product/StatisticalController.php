<?php

namespace App\Http\Controllers\Bundle\Api\Product;

use App\Bundle\ProductBundle\Application\CategoryPostCommand;
use App\Bundle\ProductBundle\Application\StatisticalDebtListGetApplicationService;
use App\Bundle\ProductBundle\Infrastructure\DebtHistoryRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
use Illuminate\Http\Request;

class StatisticalController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function getRevenues(Request $request)
    {
        $applicationService = new StatisticalDebtListGetApplicationService(
            new DebtHistoryRepository()
        );

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

}
