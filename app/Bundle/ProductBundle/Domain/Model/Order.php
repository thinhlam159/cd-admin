<?php

namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\UserId;

final class Order
{
    /**
     * @var \App\Bundle\ProductBundle\Domain\Model\OrderId
     */
    private OrderId $orderId;

    /**
     * @var \App\Bundle\Admin\Domain\Model\CustomerId
     */
    private CustomerId $customerId;

    /**
     * @var \App\Bundle\Admin\Domain\Model\UserId
     */
    private UserId $userId;

    /**
     * @var string
     */
    private string $name;

}
