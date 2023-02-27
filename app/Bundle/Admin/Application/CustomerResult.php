<?php

namespace App\Bundle\Admin\Application;

class CustomerResult
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string
     */
    public string $customerName;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $phone;

    /**
     * @var bool
     */
    public bool $isActive;

    /**
     * @param string $customerId
     * @param string $customerName
     * @param string $email
     * @param string $phone
     * @param bool $isActive
     */
    public function __construct(string $customerId, string $customerName, string $email, string $phone, bool $isActive)
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->email = $email;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }
}
