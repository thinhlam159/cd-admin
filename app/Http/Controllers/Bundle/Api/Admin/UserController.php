<?php

namespace App\Http\Controllers\Bundle\Api\Admin;

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
use App\Bundle\Admin\Infrastructure\UserRepository;
use App\Http\Controllers\Bundle\Api\Common\BaseController;
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
            $request->user_name,
            $request->email,
            Hash::make($request->password)
        );

        $result = $applicationService->handle($command);
        $data = [
            $result->userId,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request) {
        $userRepository = new UserRepository();
        $applicationService = new UserListGetApplicationService(
            $userRepository,
        );
        $command = new UserListGetCommand();
        $result = $applicationService->handle($command);
        $userManageResults = $result->userResults;
        $paginationResult = $result->paginationResult;
        $data = [];
        foreach ($userManageResults as $userManageResult) {
            $data[] = [
                'user_id' => $userManageResult->userId,
                'user_name' => $userManageResult->userName,
                'user_email' => $userManageResult->email,
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
    public function getUser(Request $request) {
        $userRepository = new UserRepository();
        $applicationService = new UserGetApplicationService($userRepository);

        $command = new UserGetCommand($request->id);
        $user = $applicationService->handle($command);
        $data = [
            'user_id' => $user->userId,
            'email' => $user->email,
            'user_name' => $user->userName,
        ];

        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function updateUser(Request $request) {
        $userRepository = new UserRepository();
        $applicationService = new UserPutApplicationService($userRepository);

        $command = new UserPutCommand(
            $request->id,
            $request->user_name,
            $request->email
        );
        $result = $applicationService->handle($command);

        return response()->json(['user_id' => $result->userId], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Bundle\Common\Domain\Model\RecordNotFoundException
     * @throws \App\Bundle\Common\Domain\Model\TransactionException
     */
    public function deleteUser(Request $request) {
        $userRepository = new UserRepository();
        $applicationService = new UserDeleteApplicationService($userRepository);

        $command = new UserDeleteCommand($request->id);

        $result = $applicationService->handle($command);

        return response()->json(['data' => []], 200);
    }
}
