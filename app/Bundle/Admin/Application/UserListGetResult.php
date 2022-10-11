<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Common\Application\PaginationResult;

class UserListGetResult
{
    public $userResults;
    public $paginationResult;

    /**
     * @param UserResult[] $userResults userResults
     * @param PaginationResult $paginationResult paginationResult
     */
    public function __construct(array $userResults, PaginationResult $paginationResult)
    {
        $this->userResults = $userResults;
        $this->paginationResult = $paginationResult;
    }
}
