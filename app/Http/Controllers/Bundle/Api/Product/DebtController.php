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

class DebtController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createPayment(Request $request)
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
}
