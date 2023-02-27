<?php

namespace App\Bundle\Admin\Application;

class CustomerPostCommand
{
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
    public ?string $password;

    /**
     * @var string|null
     */
    public ?string $phone;

    /**
     * @var bool
     */
    public bool $status;

    /**
     * @param string $customerName
     * @param string $email
     * @param string|null $password
     * @param string|null $phone
     * @param bool $status
     */
    public function __construct(
        string $customerName,
        string $email,
        ?string $password,
        ?string $phone,
        bool $status
    )
    {
        $this->customerName = $customerName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->status = $status;
    }
}
