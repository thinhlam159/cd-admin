<?php

namespace App\Bundle\Admin\Domain\Model;

interface IDealerRepository
{
    /**
     * @param \App\Bundle\Admin\Domain\Model\DealerId|null $dealerId dealerId
     * @return \App\Bundle\Admin\Domain\Model\Dealer
     */
    public function findById(DealerId $dealerId): ?Dealer;
}
