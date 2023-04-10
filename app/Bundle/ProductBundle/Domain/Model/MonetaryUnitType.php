<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class MonetaryUnitType
{
    /** @var int */
    public const VND = 1;

    /** @var array<int,string> */
    private const VALUES = [
        self::VND => 'vnd',
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
    public static function fromValue(string $value): MonetaryUnitType
    {
        foreach (self::VALUES as $type => $v) {
            if ($v === $value) {
                return new MonetaryUnitType($type);
            }
        }

        throw new InvalidArgumentException("[{$value}] Gía trị không hợp lệ");
    }

    /**
     * @param int $type type
     * @return self
     */
    public static function fromType(int $type): MonetaryUnitType
    {
        if (!isset(self::VALUES[$type])) {
            throw new InvalidArgumentException("[{$type}] Type không hợp lệ");
        }

        return new MonetaryUnitType($type);
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
