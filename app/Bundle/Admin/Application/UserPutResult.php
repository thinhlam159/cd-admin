<?php
namespace App\Bundle\Admin\Application;

final class UserPutResult
{
    /**
     * @var string
     */
    public string $userId;

    /**
     * @param string $userId userId
     */
    public function __construct(
        string $userId
    ) {
        $this->userId = $userId;
    }
}
