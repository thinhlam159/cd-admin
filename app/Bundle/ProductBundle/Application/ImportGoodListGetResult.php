<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodListGetResult
{
    /**
     * @var ImportGoodResult[]
     */
    public array $importGoodResults;

    /**
     * @param ImportGoodResult[] $importGoodResults
     */
    public function __construct(array $importGoodResults)
    {
        $this->importGoodResults = $importGoodResults;
    }
}
