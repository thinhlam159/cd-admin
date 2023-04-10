<?php

namespace App\Bundle\ProductBundle\Domain\Model;

final class OrderCriteria
{
    /**
     * @var string|null
     */
    private ?string $keyword;

    /**
     * @param string|null $keyword
     */
    public function __construct(?string $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }
}
