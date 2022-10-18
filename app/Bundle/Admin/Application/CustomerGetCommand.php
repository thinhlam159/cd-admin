<?php
namespace App\Bundle\Admin\Application;

final class CustomerGetCommand
{
    /**
     * @var string
     */
    public string $customerId;

    /**
     * @param string $customerId customerId
     */
    public function __construct(string $customerId){
        $this->customerId = $customerId;
    }
}
