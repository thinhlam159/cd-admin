<?php

namespace App\Bundle\ProductBundle\Application;

use App\Bundle\ProductBundle\Domain\Model\IMeasureUnitRepository;

class MeasureUnitListGetApplicationService
{
    /**
     * @var IMeasureUnitRepository
     */
    private IMeasureUnitRepository $measureUnitRepository;

    /**
     * @param IMeasureUnitRepository $measureUnitRepository
     */
    public function __construct(IMeasureUnitRepository $measureUnitRepository)
    {
        $this->measureUnitRepository = $measureUnitRepository;
    }

    /**
     * @param MeasureUnitListGetCommand $command
     * @return MeasureUnitListGetResult
     */
    public function handle(MeasureUnitListGetCommand $command): MeasureUnitListGetResult
    {
        $measureUnits = $this->measureUnitRepository->findAll();

        $measureUnitResults = [];
        foreach ($measureUnits as $measureUnit) {
            $measureUnitResults[] = new MeasureUnitResult(
                $measureUnit->getMeasureUnitId()->asString(),
                $measureUnit->getName(),
            );
        }

        return new MeasureUnitListGetResult(
            $measureUnitResults
        );
    }
}
