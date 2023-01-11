<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Constants\DateTimeConst;
use DateTime;

final class SettingDate
{
    /**
     * @var DateTime
     */
    private DateTime $value;

    /**
     * @param DateTime|null $dateTime dateTime
     * @return self
     */
    public static function now(?DateTime $dateTime = null): self
    {
        $now = new DateTime();
        if (!is_null($dateTime)) {
            $now = clone $dateTime;
        }

        return new self($now);
    }

    /**
     * @param string|null $value value
     * @return self
     */
    public static function fromYmdHis(?string $value): ?self
    {
        if (is_null($value)) {
            return null;
        }

        return new self(new DateTime($value));
    }

    /**
     * @param \DateTime $value value
     */
    public function __construct(
        DateTime $value
    )
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getValue(): DateTime
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function asString(): string
    {
        return $this->value->format(DateTimeConst::FORMAT_YMD);
    }

    /**
     * @return string
     */
    public function asHourString(): string
    {
        return $this->value->format(DateTimeConst::FORMAT_HIS);
    }

    /**
     * @return string
     */
    public function asMonthString(): string
    {
        return $this->value->format(DateTimeConst::FORMAT_M);
    }

    /**
     * @return string
     */
    public function asHourMinuteString(): string
    {
        return $this->value->format(DateTimeConst::FORMAT_HI);
    }

    /**
     * @return string
     */
    public function asDateTimeString(): string
    {
        return $this->value->format(DateTimeConst::FORMAT);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->asString();
    }

    /**
     * @return int
     */
    public function asTimeStamps(): int
    {
        return $this->value->format('U');
    }

    /**
     * @param int $timeStamps
     * @return SettingDate
     */
    public static function fromTimeStamps(int $timeStamps): SettingDate
    {
        $date = new DateTime();
        $date->setTimestamp($timeStamps);

        return new self($date);
    }
}
