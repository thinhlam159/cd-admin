<?php 

namespace App\Bundle\Admin\Domain\Model;

interface IUserRepository
{
    /**
     * @param User $user user
     * @return UserId
     */
    public function create(User $user): UserId;
}
