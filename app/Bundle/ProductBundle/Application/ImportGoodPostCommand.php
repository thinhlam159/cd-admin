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
     * @var string
     */
    public string $date;

    /**
     * @var string|null
     */
    public ?string $containerName;

    /**
     * @param string $userId
     * @param ImportGoodProductCommand[] $importGoodProductCommands
     * @param string $date
     * @param string|null $containerName
     */
    public function __construct(string $userId, array $importGoodProductCommands, string $date, ?string $containerName)
    {
        $this->userId = $userId;
        $this->importGoodProductCommands = $importGoodProductCommands;
        $this->date = $date;
        $this->containerName = $containerName;
    }
}
