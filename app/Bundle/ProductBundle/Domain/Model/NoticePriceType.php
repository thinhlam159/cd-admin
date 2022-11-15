<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class NoticePriceType
{
    /** @var int */
    public const DEFAULT = 1;
    /** @var int */
    public const KG298 = 2;
    /** @var int */
    public const KG273 = 3;
    /** @var int */
    public const KG248 = 4;
    /** @var int */
    public const KG224 = 5;
    /** @var int */
    public const KG214 = 6;
    /** @var int */
    public const KG190 = 7;

    /** @var array<int,string> */
    private const VALUES = [
        self::DEFAULT => '',
        self::KG298 => '298kg',
        self::KG273 => '273kg',
        self::KG248 => '248kg',
        self::KG224 => '224kg',
        self::KG214 => '214kg',
        self::KG190 => '290kg',
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
    public static function fromValue(string $value): NoticePriceType
    {
        foreach (self::VALUES as $type => $v) {
            if ($v === $value) {
                return new NoticePriceType($type);
            }
        }

        throw new InvalidArgumentException("[{$value}] 不正な値です。");
    }

    /**
     * @param int $type type
     * @return self
     */
    public static function fromType(int $type): NoticePriceType
    {
        if (!isset(self::VALUES[$type])) {
            throw new InvalidArgumentException("[{$type}] 不正な値です。");
        }

        return new NoticePriceType($type);
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
