<?php

namespace App\Bundle\Admin\Application;

class CustomerAllListGetResult
{
    public array $customerResults;

    /**
     * @param CustomerResult[] $customerResults customerResults
     */
    public function __construct(array $customerResults)
    {
        $this->customerResults = $customerResults;
    }
}
