<?php

namespace App\Bundle\Admin\Infrastructure;

use App\Bundle\Admin\Domain\Model\IUserRepository;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Admin\Domain\Model\User;
use App\Models\User as ModelsUser;
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
}