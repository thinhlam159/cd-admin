<?php
namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\RecordNotFoundException;
use App\Bundle\Common\Constants\MessageConst;

final class UserGetApplicationService
{
    /**
     * @var \App\Bundle\Admin\Domain\Model\IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @param \App\Bundle\Admin\Domain\Model\IUserRepository $userRepository userRepository
     */
    public function __construct(
        IUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Bundle\Admin\Application\UserGetCommand $command command
     * @return \App\Bundle\Admin\Application\UserGetResult
     */
    public function handle(UserGetCommand $command): UserGetResult
    {
        $user = $this->userRepository->findById(new UserId($command->userId));
        if (!$user) {
            throw new RecordNotFoundException(MessageConst::NOT_FOUND['message']);
        }

        return new UserGetResult(
            $user->getUserId()->__toString(),
            $user->getUserName(),
            $user->getEmail(),
        );
    }
}
