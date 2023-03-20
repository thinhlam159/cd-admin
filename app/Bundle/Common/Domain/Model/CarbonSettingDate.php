<?php
namespace App\Bundle\Common\Domain\Model;

use App\Bundle\Common\Constants\DateTimeConst;
use Carbon\Carbon;

final class CarbonSettingDate
{
    /**
     * @var Carbon
     */
    private Carbon $value;

    /**
     * @param Carbon|null $dateTime dateTime
     * @return self
     */
    public static function now(?Carbon $dateTime = null): self
    {
        $now = Carbon::now();
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

        return new self(new Carbon($value));
    }

    /**
     * @param Carbon $value value
     */
    public function __construct(
        Carbon $value
    )
    {
        $this->value = $value;
    }

    /**
     * @return Carbon
     */
    public function getValue(): Carbon
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
     * @param int $dayAmount
     * @return CarbonSettingDate
     */
    public function getSubDay(int $dayAmount): CarbonSettingDate
    {
        $carbon = new self(new Carbon($this->asString()));

        return new self($carbon->value->subDay($dayAmount));
    }
}
