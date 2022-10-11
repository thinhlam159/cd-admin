<?php
namespace App\Bundle\Admin\Application;

use \App\Bundle\Common\Application\PaginationResult;

final class UserGetResult
{
    /**
     * @var string
     */
    public string $userId;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $userName;

    /**
     * @param string $userId userId
     * @param string $userName userName
     * @param string $email email
     */
    public function __construct(
        string $userId,
        string $userName,
        string $email
    )
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->email = $email;
    }
}
