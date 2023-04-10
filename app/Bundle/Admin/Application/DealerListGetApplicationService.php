<?php

namespace App\Bundle\Admin\Application;

use App\Bundle\Admin\Domain\Model\IDealerRepository;
use App\Bundle\Common\Application\PaginationResult;

class DealerListGetApplicationService
{
    /**
     * @var IDealerRepository
     */
    private IDealerRepository $dealerRepository;

    /**
     * @param IDealerRepository $dealerRepository
     */
    public function __construct(IDealerRepository $dealerRepository)
    {
        $this->dealerRepository = $dealerRepository;
    }

    /**
     * @param \App\Bundle\Admin\Application\DealerListGetCommand $command
     * @return \App\Bundle\Admin\Application\DealerListGetResult
     */
    public function handle(DealerListGetCommand $command): DealerListGetResult
    {
        [$dealers, $pagination] = $this->dealerRepository->findAll();
        $dealerResults = [];
        foreach ($dealers as $dealer) {
            $dealerResults[] = new DealerResult(
                $dealer->getDealerId()->__toString(),
                $dealer->getName(),
                $dealer->getEmail(),
                $dealer->getPhone(),
                $dealer->getIsActive(),
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new DealerListGetResult(
            $dealerResults,
            $paginationResult
        );
    }
}
