<?php

namespace App\Bundle\Admin\Application;

final class CustomerPutCommand
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $customerName;

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
     * @param string $email
     * @param string $customerName
     * @param string|null $address
     * @param string|null $phone
     * @param bool $isActive
     */
    public function __construct(string $customerId, string $email, string $customerName, ?string $address, ?string $phone, bool $isActive)
    {
        $this->customerId = $customerId;
        $this->email = $email;
        $this->customerName = $customerName;
        $this->address = $address;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }
}


