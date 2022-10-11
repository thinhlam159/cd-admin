<?php

namespace App\Bundle\Admin\Application;

class UserResult
{
    public $userId;
    public $userName;
    public $email;

    /**
     * @param string $userId
     * @param string $userName
     * @param string $email
     */
    public function __construct(string $userId, string $userName, string $email)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->email = $email;
    }
}
