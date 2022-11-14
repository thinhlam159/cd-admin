<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class MeasureUnitType
{
    /** @var int */
    public const KG = 1;

    /** @var int */
    public const MET = 2;

    /** @var array<int,string> */
    private const VALUES = [
        self::KG => 'kg',
        self::MET => 'met',
    ];
    private int $type;

    /**
     * @param int $type type
     */
    private function __construct(
        int $type
    ) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->getType()}:{$this->getValue()}";
    }

    /**
     * @param string $value value
     * @return self
     */
    public static function fromValue(string $value): MeasureUnitType
    {
        foreach (self::VALUES as $type => $v) {
            if ($v === $value) {
                return new MeasureUnitType($type);
            }
        }

        throw new InvalidArgumentException("[{$value}] 不正な値です。");
    }

    /**
     * @param int $type type
     * @return self
     */
    public static function fromType(int $type): MeasureUnitType
    {
        if (!isset(self::VALUES[$type])) {
            throw new InvalidArgumentException("[{$type}] 不正な値です。");
        }

        return new MeasureUnitType($type);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return self::VALUES[$this->type];
    }
}
