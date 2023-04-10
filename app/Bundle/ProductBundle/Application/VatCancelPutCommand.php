<?php

namespace App\Bundle\ProductBundle\Application;

class VatCancelPutCommand
{
    /**
     * @var string
     */
    public string $vatId;

    /**
     * @param string $vatId
     */
    public function __construct(string $vatId)
    {
        $this->vatId = $vatId;
    }
}
