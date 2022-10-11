<?php

namespace App\Bundle\Common\Domain\Library;

use Ulid\Ulid;

class CdAdminText
{
    /**
     * @return string
     */
    public static function id(): string
    {
        return (string)Ulid::generate();
    }
}
