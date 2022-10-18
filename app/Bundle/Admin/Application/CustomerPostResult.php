<?php

namespace App\Bundle\Admin\Application;

class CustomerPostResult
{
    public string $customerId;

    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }
}
