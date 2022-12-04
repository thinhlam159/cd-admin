<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class ProductInventoryUpdateType
{
    /** @var int */
    public const ORDER = 1;

    /** @var int */
    public const IMPORT_GOOD = 2;

    /** @var array<int,string> */
    private const VALUES = [
        self::ORDER => 'order',
        self::IMPORT_GOOD => 'import_good',
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
    public static function fromValue(string $value): ProductInventoryUpdateType
    {
        foreach (self::VALUES as $type => $v) {
            if ($v === $value) {
                return new ProductInventoryUpdateType($type);
            }
        }

        throw new InvalidArgumentException("[{$value}] Giá trị không hợp lệ");
    }

    /**
     * @param int $type type
     * @return self
     */
    public static function fromType(int $type): ProductInventoryUpdateType
    {
        if (!isset(self::VALUES[$type])) {
            throw new InvalidArgumentException("[{$type}] Loại không hợp lệ");
        }

        return new ProductInventoryUpdateType($type);
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
