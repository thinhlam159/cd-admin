<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\UserId;

final class ImportGood
{
    /**
     * @var ImportGoodId
     */
    private ImportGoodId $importGoodId;
    /**
     * @var DealerId|null
     */
    private ?DealerId $dealerId;
    /**
     * @var UserId
     */
    private UserId $userId;
    /**
     * @var SettingDate
     */
    private SettingDate $date;

    /**
     * @var string|null
     */
    private ?string $containerName;

    /**
     * @param ImportGoodId $importGoodId
     * @param DealerId|null $dealerId
     * @param UserId $userId
     * @param SettingDate $date
     * @param string|null $containerName
     */
    public function __construct(ImportGoodId $importGoodId, ?DealerId $dealerId, UserId $userId, SettingDate $date, ?string $containerName)
    {
        $this->importGoodId = $importGoodId;
        $this->dealerId = $dealerId;
        $this->userId = $userId;
        $this->date = $date;
        $this->containerName = $containerName;
    }

    /**
     * @return ImportGoodId
     */
    public function getImportGoodId(): ImportGoodId
    {
        return $this->importGoodId;
    }

    /**
     * @return DealerId|null
     */
    public function getDealerId(): ?DealerId
    {
        return $this->dealerId;
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

    /**
     * @return string|null
     */
    public function getContainerName(): ?string
    {
        return $this->containerName;
    }
}
