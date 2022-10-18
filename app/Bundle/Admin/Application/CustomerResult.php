<?php

namespace App\Bundle\Admin\Application;

class CustomerResult
{
    public string $customerId;
    public string $customerName;
    public string $email;
    public ?int $phone;
    public bool $isActive;

    /**
     * @param string $customerId
     * @param string $customerName
     * @param string $email
     * @param int|null $phone
     * @param bool $isActive
     */
    public function __construct(string $customerId, string $customerName, string $email, ?int $phone, bool $isActive)
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->email = $email;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }
}
