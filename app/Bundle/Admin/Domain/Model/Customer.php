<?php

namespace App\Bundle\Admin\Domain\Model;

final class Customer
{
    /**
     * @var CustomerId
     */
    private CustomerId $customerId;

    /**
     * @var string
     */
    private string $customerName;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string|null
     */
    private ?string $password;

    /**
     * @var string|null
     */
    private ?string $phone;

    /**
     * @var string|null
     */
    private ?string $address;

    /**
     * @var bool|null
     */
    private ?bool $isActive;

    /**
     * @param \App\Bundle\Admin\Domain\Model\CustomerId $customerId customerId
     * @param string $customerName
     * @param string $email
     * @param string|null $password
     * @param string|null $address
     * @param string|null $phone
     * @param bool $isActive
     */
    public function __construct(
        CustomerId $customerId,
        string $customerName,
        string $email,
        ?string $password = null,
        ?string $address = null,
        ?string $phone = null,
        ?bool $isActive = false
    )
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
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
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
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
