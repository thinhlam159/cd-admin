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
     * @var string|null
     */
    public ?string $address;

    /**
     * @var string|null
     */
    public ?string $phone;

    /**
     * @var bool
     */
    public bool $isActive;

    /**
     * @param string $customerId
     * @param string $customerName
     * @param string $email
     * @param string|null $address
     * @param string|null $phone
     * @param bool $isActive
     */
    public function __construct(string $customerId, string $customerName, string $email, ?string $address, ?string $phone, bool $isActive)
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->email = $email;
        $this->address = $address;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }
}
