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

final class UserDeleteApplicationService
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
     * @param \App\Bundle\Admin\Application\UserDeleteCommand $command command
     * @return \App\Bundle\Admin\Application\UserDeleteResult
     */
    public function handle(UserDeleteCommand $command): UserPutResult
    {
        $userId = new UserId($command->userId);
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new RecordNotFoundException(MessageConst::NOT_FOUND['message']);
        }

        DB::beginTransaction();
        try {
            $result = $this->userRepository->delete($userId);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new TransactionException('Delete user failed!');
        }

        return new UserPutResult(
            $userId->__toString()
        );
    }
}
