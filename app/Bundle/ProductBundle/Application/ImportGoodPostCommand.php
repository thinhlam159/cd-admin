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
     * @var int
     */
    public int $date;

    /**
     * @param string $userId
     * @param ImportGoodProductCommand[] $importGoodProductCommands
     * @param int $date
     */
    public function __construct(string $userId, array $importGoodProductCommands, int $date)
    {
        $this->userId = $userId;
        $this->importGoodProductCommands = $importGoodProductCommands;
        $this->date = $date;
    }
}
