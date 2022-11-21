<?php
namespace App\Bundle\ProductBundle\Domain\Model;

final class MeasureUnit
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\MeasureUnitId
     */
    private MeasureUnitId $measureUnitId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param \App\Bundle\ProductBundle\Domain\Model\MeasureUnitId $measureUnitId measureUnitId
     * @param string $name name
     */
    public function __construct(MeasureUnitId $measureUnitId, string $name)
    {
        $this->measureUnitId = $measureUnitId;
        $this->name = $name;
    }

    /**
     * @return \App\Bundle\ProductBundle\Domain\Model\MeasureUnitId
     */
    public function getMeasureUnitId(): MeasureUnitId
    {
        return $this->measureUnitId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
