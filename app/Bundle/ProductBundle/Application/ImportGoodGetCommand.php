<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodGetCommand
{
    /**
     * @var string
     */
    public string $importGoodId;

    /**
     * @param string $importGoodId
     */
    public function __construct(string $importGoodId)
    {
        $this->importGoodId = $importGoodId;
    }
}
