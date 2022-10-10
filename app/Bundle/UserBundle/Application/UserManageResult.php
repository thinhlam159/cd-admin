<?php
namespace App\Bundle\UserBundle\Application;

final class UserManageResult
{
    /**
     * @var int
     */
    public int $userId;

    /**
     * @var string
     */
    public string $userEmail;

    /**
     * @var string
     */
    public string $userName;

    /**
     * @var string
     */
    public string $companyName;

    /**
     * @var string
     */
    public string $userType;

    /**
     * @var bool
     */
    public bool $userActive;

    /**
     * @var string
     */
    public string $registerDate;

    /**
     * @var string
     */
    public string $loginLastDate;

    /**
     * @param int $userId userId
     * @param string $userEmail userEmail
     * @param string $userName userName
     * @param string $companyName companyName
     * @param string $userType userType
     * @param bool $userActive userActive
     * @param string $registerDate registerDate
     * @param string $loginLastDate loginLastDate
     */
    public function __construct(
        int $userId,
        string $userEmail,
        string $userName,
        string $companyName,
        string $userType,
        bool $userActive,
        string $registerDate,
        string $loginLastDate
    ) {
        $this->loginLastDate = $loginLastDate;
        $this->registerDate = $registerDate;
        $this->userActive = $userActive;
        $this->userType = $userType;
        $this->companyName = $companyName;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userId = $userId;
    }
}
