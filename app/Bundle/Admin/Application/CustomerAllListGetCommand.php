<?php

namespace App\Bundle\Admin\Application;

class CustomerAllListGetCommand
{
    /**
     * @var string|null
     */
    public ?string $keyword;

    /**
     * @param string|null $keyword
     */
    public function __construct(?string $keyword)
    {
        $this->keyword = $keyword;
    }
}
