<?php

namespace App\Bundle\ProductBundle\Application;

class ExportGoodResult
{
    /**
     * @var string
     */
    public string $exportGoodId;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var string
     */
    public string $userName;

    /**
     * @var string
     */
    public string $exportGoodDate;

    /**
     * @var ExportGoodProductResult[]
     */
    public array $exportGoodProductResults;

    /**
     * @param string $exportGoodId
     * @param string $userId
     * @param string $userName
     * @param string $exportGoodDate
     * @param ExportGoodProductResult[] $exportGoodProductResults
     */
    public function __construct(string $exportGoodId, string $userId, string $userName, string $exportGoodDate, array $exportGoodProductResults)
    {
        $this->exportGoodId = $exportGoodId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->exportGoodDate = $exportGoodDate;
        $this->exportGoodProductResults = $exportGoodProductResults;
    }
}
