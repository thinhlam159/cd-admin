<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class DebtHistoryUpdateType
{
    /** @var int */
    public const ORDER = 1;

    /** @var int */
    public const CONTAINER_ORDER = 2;

    /** @var int */
    public const VAT = 3;

    /** @var int */
    public const PAYMENT = 4;

    /** @var int */
    public const OTHER_DEBT = 5;

    /** @var int */
    public const INIT = 6;

    /** @var array<int,string> */
    private const VALUES = [
        self::ORDER => 'order',
        self::CONTAINER_ORDER => 'container_order',
        self::VAT => 'vat',
        self::PAYMENT => 'payment',
        self::OTHER_DEBT => 'other_debt',
        self::INIT => 'init',
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
    public static function fromValue(string $value): DebtHistoryUpdateType
    {
        foreach (self::VALUES as $type => $v) {
            if ($v === $value) {
                return new DebtHistoryUpdateType($type);
            }
        }

        throw new InvalidArgumentException("[{$value}] Giá trị không hợp lệ");
    }

    /**
     * @param int $type type
     * @return self
     */
    public static function fromType(int $type): DebtHistoryUpdateType
    {
        if (!isset(self::VALUES[$type])) {
            throw new InvalidArgumentException("[{$type}] Loại không hợp lệ");
        }

        return new DebtHistoryUpdateType($type);
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
