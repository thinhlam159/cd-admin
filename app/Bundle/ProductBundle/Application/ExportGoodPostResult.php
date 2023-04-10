<?php

namespace App\Bundle\ProductBundle\Application;

class ExportGoodPostResult
{
    /**
     * @var string
     */
    public string $exportGoodId;

    /**
     * @param string $exportGoodId
     */
    public function __construct(string $exportGoodId)
    {
        $this->exportGoodId = $exportGoodId;
    }
}
