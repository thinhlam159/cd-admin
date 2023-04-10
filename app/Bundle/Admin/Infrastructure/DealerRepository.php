<?php

namespace App\Bundle\Admin\Infrastructure;

use App\Bundle\Admin\Domain\Model\Dealer;
use App\Bundle\Admin\Domain\Model\DealerId;
use App\Bundle\Admin\Domain\Model\IDealerRepository;
use App\Bundle\Admin\Domain\Model\User;
use App\Bundle\Admin\Domain\Model\UserId;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Models\Dealer as ModelDealer;
use InvalidArgumentException;
use PHPUnit\Framework\Exception;

class DealerRepository implements IDealerRepository
{
    /**
     * @inheritDoc
     */
    public function findById(DealerId $dealerId): ?Dealer
    {
        $entity = ModelDealer::find($dealerId->__toString());
        if (!$entity) {
            return null;
        }

        return new Dealer(
            $dealerId,
            $entity->name,
            $entity->email,
            $entity->password,
            $entity->phone,
            $entity->is_active,
        );
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelDealer::paginate(PaginationConst::PAGINATE_ROW);

        /** @var \App\Bundle\Admin\Domain\Model\Dealer[] $result */
        $dealers = [];
        foreach ($entities as $entity) {
            $dealer = new Dealer(
                new DealerId($entity->id),
                $entity->name,
                $entity->email,
                null,
                $entity->phone,
                $entity->is_active,
            );

            $dealers[] = $dealer;
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$dealers, $pagination];
    }
}
