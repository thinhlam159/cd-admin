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
     * @param ImportGoodId $importGoodId
     * @param DealerId|null $dealerId
     * @param UserId $userId
     */
    public function __construct(ImportGoodId $importGoodId, ?DealerId $dealerId, UserId $userId)
    {
        $this->importGoodId = $importGoodId;
        $this->dealerId = $dealerId;
        $this->userId = $userId;
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
}
