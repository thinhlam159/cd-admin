<?php

namespace App\Bundle\Admin\Domain\Model;

interface IUserRepository
{
    /**
     * @param User $user user
     * @return UserId
     */
    public function create(User $user): UserId;

    /**
     * @noparam
     * @return array{\App\Bundle\Admin\Domain\Model\User[], \App\Bundle\UserBundle\Domain\Model\Pagination}
     */
    public function findAll(): array;
}
