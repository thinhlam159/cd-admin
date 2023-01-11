<?php

namespace App\Bundle\ProductBundle\Application;

class ContainerOrderPostResult
{
    /**
     * @var string
     */
    public string $containerOrderId;

    /**
     * @param string $containerOrderId
     */
    public function __construct(string $containerOrderId)
    {
        $this->containerOrderId = $containerOrderId;
    }
}
