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
     * @var int
     */
    public int $phone;

    /**
     * @var bool
     */
    public bool $isActive;

    /**
     * @param string $customerId customerId
     * @param string $customerName customerName
     * @param string $email email
     * @param int $phone $phone
     * @param bool $isActive $isActive
     */
    public function __construct(
        string $customerId,
        string $customerName,
        string $email,
        int $phone,
        bool $isActive
    ){
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->email = $email;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }
}


