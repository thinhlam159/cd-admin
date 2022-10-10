<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Common\Application\PaginationResult;

class UserListGetApplicationService
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(UserListGetCommand $command): UserListGetResult
    {
        [$users, $pagination] = $this->userRepository->findAll();

        $userResults = [];
        foreach ($users as $user) {
            $userResults[] = new UserResult(
                $user->getUserId()->__toString(),
                $user->getUserName(),
                $user->getEmail(),
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new UserListGetResult(
            $userResults,
            $paginationResult
        );
    }
}
