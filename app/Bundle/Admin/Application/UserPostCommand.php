<?php

namespace App\Bundle\Admin\Application;

class UserPostCommand
{
    public $userName;
    public $email;
    public $password;

    public function __construct(string $userName, string $email, string $password)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
    }
}