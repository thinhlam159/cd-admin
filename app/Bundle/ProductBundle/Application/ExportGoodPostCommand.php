<?php

namespace App\Bundle\ProductBundle\Application;

class ExportGoodPostCommand
{
    /**
     * @var string
     */
    public string $userId;

    /**
     * @var string
     */
    public string $date;

    /**
     * @var ExportGoodProductCommand[]
     */
    public array $exportGoodProductCommands;

    /**
     * @param string $userId
     * @param string $date
     * @param ExportGoodProductCommand[] $exportGoodProductCommands
     */
    public function __construct(string $userId, string $date, array $exportGoodProductCommands)
    {
        $this->userId = $userId;
        $this->date = $date;
        $this->exportGoodProductCommands = $exportGoodProductCommands;
    }
}
