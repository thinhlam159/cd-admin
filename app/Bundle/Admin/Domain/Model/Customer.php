<?php

namespace App\Bundle\Admin\Domain\Model;

final class Customer
{
    private CustomerId $customerId;
    private string $customerName;
    private string $email;
    private ?string $password = null;
    private ?int $phone = null;
    private ?bool $isActive = false;

    /**
     * @param \App\Bundle\Admin\Domain\Model\CustomerId $customerId customerId
     * @param string $customerName
     * @param string $email
     */
    public function __construct(
        CustomerId $customerId,
        string $customerName,
        string $email,
        ?string $password = null,
        ?int $phone = null,
        ?bool $isActive = false
    )
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }

    /**
     * @return \App\Bundle\Admin\Domain\Model\CustomerId
     */
    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
    * @return string
    */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    /**
    * @return string
    */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string|null $password password
     * @return void
     */
    public function setPassword(?string $password): void{
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return int|null
     */
    public function getPhone(): ?int
    {
        return $this->phone;
    }

    /**
     * @param int|null $phone
     */
    public function setPhone(?int $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }
}
