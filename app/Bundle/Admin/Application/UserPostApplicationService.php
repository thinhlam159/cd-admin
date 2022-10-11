<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\Common\Domain\Model\TransactionException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPostApplicationService
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(UserPostCommand $command): UserPostResult
    {
        $existingEmail = $this->userRepository->checkExistingEmail($command->email);
        if (!$existingEmail) {
            throw new InvalidArgumentException('Existing Email!');
        }
        $userId = UserId::newId();
        $user = new User(
            $userId,
            $command->userName,
            $command->email,
        );
        $user->setPassword($command->password);

        DB::beginTransaction();
        try {
            $userId = $this->userRepository->create($user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Add user fail!');
        }

        return new UserPostResult($userId->__toString());
    }
}
