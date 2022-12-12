<?php

namespace App\Bundle\Admin\Domain\Model;

final class Dealer
{
    /**
     * @var DealerId
     */
    private DealerId $dealerId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string|null
     */
    private ?string $phone;

    /**
     * @var bool
     */
    private bool $isActive;

    /**
     * @param DealerId $dealerId
     * @param string $name
     * @param string|null $email
     * @param string $password
     * @param string|null $phone
     * @param bool $isActive
     */
    public function __construct(DealerId $dealerId, string $name, ?string $email, string $password, ?string $phone, bool $isActive)
    {
        $this->dealerId = $dealerId;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->isActive = $isActive;
    }

    /**
     * @return DealerId
     */
    public function getDealerId(): DealerId
    {
        return $this->dealerId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
