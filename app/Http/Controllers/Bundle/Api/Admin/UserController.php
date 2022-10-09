<?php 

namespace App\Http\Controllers\Bundle\Api\Admin;

use App\Bundle\Api\Common\BaseController;

class UserController extends BaseController
{
    /**
     * 
     */
    public function createUser()
    {
        $userRepository = new UserRepository();
        $applicationService = new UserPostApplicationService($userRepository);

        $command = new UserPostCommand();

        $result = $applicationService->handle($command);
        $data = [];

        return response()->json([
            'data' => $data,
        ]);
    }
}