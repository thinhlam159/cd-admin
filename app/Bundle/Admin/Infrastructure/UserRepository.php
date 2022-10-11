<?php

namespace App\Bundle\Admin\Infrastructure;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\User as ModelUser;
use InvalidArgumentException;
use PHPUnit\Framework\Exception;

class UserRepository implements IUserRepository
{
    public function create(User $user): UserId
    {
        $result = ModelUser::create([
            'id' => $user->getUserId()->__toString(),
            'email' => $user->getEmail(),
            'name' => $user->getUserName(),
            'password' => $user->getPassword(),
        ]);

        if(!$result) {
            throw new InvalidArgumentException();
        }

        return $user->getUserId();
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelUser::paginate(PaginationConst::PAGINATE_ROW);

        /** @var \App\Bundle\Admin\Domain\Model\User[] $result */
        $users = [];
        foreach ($entities as $entity) {
            $users[] = new User(
                new UserId($entity->id),
                $entity->name,
                $entity->email
            );
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$users, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findById(UserId $userId): ?User
    {
        $entity = ModelUser::find($userId->__toString());
        if (!$entity) {
            return null;
        }

        return new User(
            $userId,
            $entity->name,
            $entity->email,
        );
    }

    /**
     * @inheritDoc
     */
    public function update(User $user): UserId
    {
        $entity = ModelUser::find($user->getUserId()->__toString());

        $data = [
            'name' => $user->getUserName(),
            'email' => $user->getEmail(),
        ];
        if ($user->getPassword()) {
            $data['password'] = $user->getPassword();
        }
        $result = $entity->update($data);

        return $user->getUserId();
    }

    /**
     * @inheritDoc
     */
    public function delete(UserId $userId): bool
    {
        $entity = ModelUser::find($userId->__toString());
        $result = $entity->delete();

//        $result = ModelUser::delete($entity);
        if (!$result) {
            throw new Exception();
        }

        return true;
    }

    /**
     * @param string $email email
     * @return bool
     */
    public function checkExistingEmail(string $email): bool
    {
        $entity = ModelUser::where('email', '=' , $email)->first();

        if ($entity) {
            return false;
        }

        return true;
    }
}
