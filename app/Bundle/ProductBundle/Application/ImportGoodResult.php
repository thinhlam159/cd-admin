<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodResult
{
    /**
     * @var string
     */
    public string $importGoodId;
    /**
     * @var string
     */
    public string $dealerId;
    /**
     * @var string
     */
    public string $dealerName;
    /**
     * @var string
     */
    public string $userId;
    /**
     * @var string
     */
    public string $userName;
    /**
     * @var ImportGoodProductResult[]
     */
    public array $importGoodProductResults;

    /**
     * @param string $importGoodId
     * @param string $dealerId
     * @param string $dealerName
     * @param string $userId
     * @param string $userName
     * @param ImportGoodProductResult[] $importGoodProductResults
     */
    public function __construct(string $importGoodId, string $dealerId, string $dealerName, string $userId, string $userName, array $importGoodProductResults)
    {
        $this->importGoodId = $importGoodId;
        $this->dealerId = $dealerId;
        $this->dealerName = $dealerName;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->importGoodProductResults = $importGoodProductResults;
    }
}
