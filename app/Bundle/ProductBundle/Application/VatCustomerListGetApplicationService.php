<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\Common\Domain\Model\InvalidArgumentException;
use App\Bundle\ProductBundle\Domain\Model\IVatRepository;

class VatCustomerListGetApplicationService
{
    /**
     * @var IVatRepository
     */
    private IVatRepository $vatRepository;

    /**
     * @param IVatRepository $vatRepository
     */
    public function __construct(IVatRepository $vatRepository)
    {
        $this->vatRepository = $vatRepository;
    }

    /**
     * @param VatCustomerListGetCommand $command
     * @return VatCustomerListGetResult
     * @throws InvalidArgumentException
     */
    public function handle(VatCustomerListGetCommand $command): VatCustomerListGetResult
    {
        $customerId = new CustomerId($command->customerId);
        [$vats, $pagination] = $this->vatRepository->findAllByCustomerId($customerId);
        $vatResults = [];
        foreach ($vats as $vat) {
            $vatResults[] = new VatResult(
                $vat->getVatId()->asString(),
                $vat->getCost(),
                $vat->getMonetaryUnitType()->getValue(),
                $vat->getComment(),
                $vat->getCustomerId()->asString(),
                $vat->getUserId()->asString(),
                $vat->getArisingDate(),
                $vat->getPaymentStatus()->getValue(),
            );
        }

        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new VatCustomerListGetResult($paginationResult, $vatResults);
    }
}
