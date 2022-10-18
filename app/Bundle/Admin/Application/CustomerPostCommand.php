<?php

namespace App\Bundle\Admin\Application;

class CustomerPostCommand
{
    public string $customerName;
    public string $email;
    public ?string $password;
    public ?int $phone;

    public function __construct(string $customerName, string $email, ?string $password, ?int $phone)
    {
        $this->customerName = $customerName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
    }
}
