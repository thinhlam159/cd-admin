<?php

namespace App\Bundle\ProductBundle\Application;

class MeasureUnitResult
{
    /**
     * @var string
     */
    public string $measureUnitId;

    /**
     * @var string
     */
    public string $name;

    /**
     * @param string $measureUnitId
     * @param string $name
     */
    public function __construct(string $measureUnitId, string $name)
    {
        $this->measureUnitId = $measureUnitId;
        $this->name = $name;
    }
}
