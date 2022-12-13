<?php

namespace App\Bundle\ProductBundle\Application;

class ImportGoodPostCommand
{
    /**
     * @var string
     */
    public string $userId;

    /**
     * @var ImportGoodProductCommand[]
     */
    public array $importGoodProductCommands;

    /**
     * @param string $userId
     * @param ImportGoodProductCommand[] $importGoodProductCommands
     */
    public function __construct(string $userId, array $importGoodProductCommands)
    {
        $this->userId = $userId;
        $this->importGoodProductCommands = $importGoodProductCommands;
    }
}
