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

    /**
     * @param \App\Bundle\Admin\Domain\Model\UserId|null $userId userId
     * @return \App\Bundle\Admin\Domain\Model\User
     */
    public function findById(UserId $userId): ?User;

    /**
     * @param User $user user
     * @return UserId
     */
    public function update(User $user): UserId;

    /**
     * @param UserId $userId userId
     * @return bool
     */
    public function delete(UserId $userId): bool;
}
