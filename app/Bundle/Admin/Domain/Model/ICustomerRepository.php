<?php

namespace App\Bundle\Admin\Domain\Model;

interface ICustomerRepository
{
    /**
     * @param Customer $customer customer
     * @return CustomerId
     */
    public function create(Customer $customer): CustomerId;

    /**
     * @noparam
     * @return array{\App\Bundle\Admin\Domain\Model\Customer[], \App\Bundle\UserBundle\Domain\Model\Pagination}
     */
    public function findAll(): array;

    /**
     * @param \App\Bundle\Admin\Domain\Model\CustomerId $customerId customerId
     * @return \App\Bundle\Admin\Domain\Model\Customer|null
     */
    public function findById(CustomerId $customerId): ?Customer;

    /**
     * @param Customer $customer customer
     * @return CustomerId
     */
    public function update(Customer $customer): CustomerId;

    /**
     * @param \App\Bundle\Admin\Domain\Model\CustomerId $customerId customerId
     * @return bool
     */
    public function delete(CustomerId $customerId): bool;

    /**
     * @param string $keyword
     * @return \App\Bundle\Admin\Domain\Model\Customer[]
     */
    public function findAllByKeyword(string $keyword): array;
}
