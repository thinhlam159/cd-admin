<?php

namespace App\Bundle\ProductBundle\Application;

class MeasureUnitListGetResult
{
    /**
     * @var MeasureUnitResult[]
     */
    public array $measureUnitResults;

    /**
     * @param MeasureUnitResult[] $measureUnitResults
     */
    public function __construct(array $measureUnitResults)
    {
        $this->measureUnitResults = $measureUnitResults;
    }
}
