<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodResult
{
    /**
     * @var string
     */
    public string $importGoodId;
    /**
     * @var string|null
     */
    public ?string $dealerId;
    /**
     * @var string|null
     */
    public ?string $dealerName;
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
    public string $importGoodDate;
    /**
     * @var string|null
     */
    public ?string $containerName;
    /**
     * @var ImportGoodProductResult[]
     */
    public array $importGoodProductResults;

    /**
     * @param string $importGoodId
     * @param string|null $dealerId
     * @param string|null $dealerName
     * @param string $userId
     * @param string $userName
     * @param string $importGoodDate
     * @param string|null $containerName
     * @param ImportGoodProductResult[] $importGoodProductResults
     */
    public function __construct(string $importGoodId, ?string $dealerId, ?string $dealerName, string $userId, string $userName, string $importGoodDate, ?string $containerName, array $importGoodProductResults)
    {
        $this->importGoodId = $importGoodId;
        $this->dealerId = $dealerId;
        $this->dealerName = $dealerName;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->importGoodDate = $importGoodDate;
        $this->containerName = $containerName;
        $this->importGoodProductResults = $importGoodProductResults;
    }
}
