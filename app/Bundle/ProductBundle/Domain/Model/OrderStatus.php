<?php
declare(strict_types=1);

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class OrderStatus
{
    /** @var int */
    public const IN_PROGRESS = 1;
    /** @var int */
    public const RESOLVED = 2;
    /** @var int */
    public const CANCEL = 3;

    /** @var array<int,string> */
    private const VALUES = [
        self::IN_PROGRESS => 'in_progress',
        self::RESOLVED => 'resolved',
        self::CANCEL => 'cancel',
    ];
    /**
     * @var int
     */
    private $status;

    /**
     * @param int $status status
     */
    private function __construct(
        int $status
    ) {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->getStatus()}:{$this->getValue()}";
    }

    /**
     * @param string $value value
     * @return self
     */
    public static function fromValue(string $value): OrderStatus
    {
        foreach (self::VALUES as $status => $v) {
            if ($v === $value) {
                return new OrderStatus($status);
            }
        }

        throw new InvalidArgumentException("Giá trị [{$status}] không hợp lệ");
    }

    /**
     * @param int $status status
     * @return self
     */
    public static function fromStatus(int $status): OrderStatus
    {
        if (!isset(self::VALUES[$status])) {
            throw new InvalidArgumentException("Giá trị [{$status}] không hợp lệ");
        }

        return new OrderStatus($status);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return self::VALUES[$this->status];
    }
}
