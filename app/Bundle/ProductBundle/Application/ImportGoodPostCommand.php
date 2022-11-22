<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodPostCommand
{
    /**
     * @var string
     */
    public string $dealerId;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var ImportGoodProductCommand[]
     */
    public array $importGoodProductCommands;

    /**
     * @param string $dealerId
     * @param string $userId
     * @param ImportGoodProductCommand[] $importGoodProductCommands
     */
    public function __construct(string $dealerId, string $userId, array $importGoodProductCommands)
    {
        $this->dealerId = $dealerId;
        $this->userId = $userId;
        $this->importGoodProductCommands = $importGoodProductCommands;
    }
}
