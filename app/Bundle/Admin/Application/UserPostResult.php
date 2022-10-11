<?php

namespace App\Bundle\Admin\Application;

class UserPostResult
{
    public $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}