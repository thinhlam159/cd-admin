<?php

namespace App\Http\Controllers\Bundle\Api\Admin;

use App\Bundle\Admin\Application\UserListGetApplicationService;
use App\Bundle\Admin\Application\UserListGetCommand;
use App\Bundle\Admin\Application\UserPostApplicationService;
use App\Bundle\Admin\Application\UserPostCommand;
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Bundle\Api\Common\BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * @param Request $request request
     */
    public function createUser(Request $request)
    {
        $userRepository = new UserRepository();
        $applicationService = new UserPostApplicationService($userRepository);

        $command = new UserPostCommand(
            $request->name,
            $request->email,
            Hash::make($request->password)
        );

        $result = $applicationService->handle($command);
        $data = [
            $result->userId,
        ];

        return response()->json($data);
    }

    public function getUsers(Request $request) {
        $userRepository = new UserRepository();
        $applicationService = new UserListGetApplicationService(
            $userRepository,
        );
        $command = new UserListGetCommand();

        $result = $applicationService->handle($command);
        $userManageResults = $result->userManageResult;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($userManageResults as $userManageResult) {
            $data[] = [
                'user_id' => $userManageResult->userId,
                'user_email' => $userManageResult->userEmail,
                'user_name' => $userManageResult->userName,
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
