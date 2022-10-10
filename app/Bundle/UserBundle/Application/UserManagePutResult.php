<?php
namespace App\Bundle\UserBundle\Application;

final class UserManagePutResult
{
    /**
     * @var int
     */
    public int $userId;

    /**
     * @param int $userId userId
     */
    public function __construct(
        int $userId
    ) {
        $this->userId = $userId;
    }
}
