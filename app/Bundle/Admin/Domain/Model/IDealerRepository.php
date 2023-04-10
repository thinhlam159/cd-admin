<?php

namespace App\Bundle\Admin\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IDealerRepository
{
    /**
     * @param \App\Bundle\Admin\Domain\Model\DealerId|null $dealerId dealerId
     * @return \App\Bundle\Admin\Domain\Model\Dealer
     */
    public function findById(DealerId $dealerId): ?Dealer;

    /**
     * @noparam
     * @return array{Dealer[], Pagination}
     */
    public function findAll(): array;
}
