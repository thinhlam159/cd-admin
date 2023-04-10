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
     * @var string|null
     */
    private ?string $password;

    /**
     * @var string|null
     */
    private ?string $phone;

    /**
     * @var bool|null
     */
    private ?bool $isActive;

    /**
     * @param DealerId $dealerId
     * @param string $name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $phone
     * @param bool|null $isActive
     */
    public function __construct(DealerId $dealerId, string $name, ?string $email = null, ?string $password = null, ?string $phone = null, ?bool $isActive = null)
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
     * @return string|null
     */
    public function getPassword(): ?string
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
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }
}
