<?php

namespace App\Bundle\Admin\Application;

class CustomerListGetCommand
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
