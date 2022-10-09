<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Application\UserPostCommand;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Admin\Domain\Model\UserId;

class UserPostApplicationService
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(UserPostCommand $command): UserPostResult
    {
        $userId = UserId::newId();
        $user = new User(
            $userId,
            $command->userName,
            $command->email,
        );
        $user->setPassword($command->password);

        $userId = $this->userRepository->create($user);

        return new UserPostResult($userId->__toString());
    }
}