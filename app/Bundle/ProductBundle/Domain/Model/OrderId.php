<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Library\CdAdminText;
use \App\Bundle\Common\Domain\Model\ValueObjectStringTrait;
use \App\Bundle\Common\Domain\Model\InvalidArgumentException;

class OrderId
{
    use ValueObjectStringTrait;

    private $value;

    /**
     * @param string $value value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
        if(!self::validate($value)) {
            throw new InvalidArgumentException("[{$value}] invalid value");
        }
    }

    /**
     * @return self
     */
    public static function newId(): self
    {
        return new self(CdAdminText::id());
    }

    /**
     * @param string $value value
     * @return bool
     */
    public static function validate(string $value) {
        if($value === '') return false;

        return true;
    }

    /**
     * @param self $obj obj
     * @return bool
     */
    public function equals(OrderId $obj): bool
    {
        return $this->value === $obj->value;
    }
}
