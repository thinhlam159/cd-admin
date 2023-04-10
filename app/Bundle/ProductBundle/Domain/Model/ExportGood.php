<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\UserId;

final class ExportGood
{
    /**
     * @var ExportGoodId
     */
    private ExportGoodId $exportGoodId;

    /**
     * @var UserId
     */
    private UserId $userId;

    /**
     * @var SettingDate
     */
    private SettingDate $date;

    /**
     * @param ExportGoodId $exportGoodId
     * @param UserId $userId
     * @param SettingDate $date
     */
    public function __construct(ExportGoodId $exportGoodId, UserId $userId, SettingDate $date)
    {
        $this->exportGoodId = $exportGoodId;
        $this->userId = $userId;
        $this->date = $date;
    }

    /**
     * @return ExportGoodId
     */
    public function getExportGoodId(): ExportGoodId
    {
        return $this->exportGoodId;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return SettingDate
     */
    public function getDate(): SettingDate
    {
        return $this->date;
    }
}
