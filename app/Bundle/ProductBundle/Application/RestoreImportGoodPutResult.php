<?php

namespace App\Bundle\ProductBundle\Application;

class RestoreImportGoodPutResult
{
    /**
     * @var string
     */
    public string $importGood;

    /**
     * @param string $importGood
     */
    public function __construct(string $importGood)
    {
        $this->importGood = $importGood;
    }
}
