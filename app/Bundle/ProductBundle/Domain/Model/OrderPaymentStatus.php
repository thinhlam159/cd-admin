<?php
declare(strict_types=1);

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\InvalidArgumentException;

final class OrderPaymentStatus
{
    /** @var int */
    public const PLANNING = 1;
    /** @var int */
    public const PENDING = 2;
    /** @var int */
    public const DONE = 3;
    /** @var int */
    public const CANCEL = 4;

    /** @var array<int,string> */
    private const VALUES = [
        self::PLANNING => 'planning',
        self::PENDING => 'pending',
        self::DONE => 'done',
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
    public static function fromValue(string $value): OrderPaymentStatus
    {
        foreach (self::VALUES as $status => $v) {
            if ($v === $value) {
                return new OrderPaymentStatus($status);
            }
        }

        throw new InvalidArgumentException("Giá trị [{$status}] không hợp lệ");
    }

    /**
     * @param int $status status
     * @return self
     */
    public static function fromStatus(int $status): OrderPaymentStatus
    {
        if (!isset(self::VALUES[$status])) {
            throw new InvalidArgumentException("Giá trị [{$status}] không hợp lệ");
        }

        return new OrderPaymentStatus($status);
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
