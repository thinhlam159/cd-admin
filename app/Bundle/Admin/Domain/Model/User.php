<?php

namespace App\Bundle\Admin\Domain\Model;
final class User
{
    private $userId;
    private $userName;
    private $email;
    private $password;

    /**
     * @param \App\Bundle\Admin\Domain\Model\UserId $userId userId
     * @param string $userName
     * @param string $email
     */
    public function __construct(UserId $userId, string $userName, string $email)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->email = $email;
    }

    /**
     * @return \App\Bundle\Admin\Domain\Model\UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
    * @return string
    */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
    * @return string
    */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $password password
     * @return void
     */
    public function setPassword(string $password): void{
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
