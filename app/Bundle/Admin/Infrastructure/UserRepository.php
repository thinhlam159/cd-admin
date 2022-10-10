<?php

namespace App\Bundle\Admin\Infrastructure;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\User as ModelsUser;
use App\Models\User as ModelUser;
use InvalidArgumentException;
use PHPUnit\Framework\Exception;

class UserRepository implements IUserRepository
{
    public function create(User $user): UserId
    {
        $result = ModelsUser::create([
            'id' => $user->getUserId()->__toString(),
            'email' => $user->getEmail(),
            'user_name' => $user->getUserName(),
            'password' => $user->getPassword()
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
                $entity->userName,
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
}
