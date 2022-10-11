<?php
namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Domain\Model\RecordNotFoundException;
use App\Bundle\Common\Domain\Model\TransactionException;
use App\Bundle\Common\Constants\MessageConst;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class UserPutApplicationService
{
    /**
     * @var IUserRepository
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
     * @param \App\Bundle\Admin\Application\UserPutCommand $command command
     * @return \App\Bundle\Admin\Application\UserPutResult
     */
    public function handle(UserPutCommand $command): UserPutResult
    {
        $user = $this->userRepository->findById(new UserId($command->userId));
        if (!$user) {
            throw new RecordNotFoundException(MessageConst::NOT_FOUND['message']);
        }

        $user= new User(
            new UserId($command->userId),
            $command->userName,
            $command->email,
        );

        DB::beginTransaction();
        try {
            $userId = $this->userRepository->update($user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Update user failed!');
        }

        return new UserPutResult(
            $userId->__toString()
        );
    }
}
