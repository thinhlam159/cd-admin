<?php

namespace App\Bundle\Admin\Infrastructure;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\Common\Domain\Model\Pagination;
use App\Models\Customer as ModelCustomer;
use Exception;
use InvalidArgumentException;

class CustomerRepository implements ICustomerRepository
{
    public function create(Customer $customer): CustomerId
    {
        $result = ModelCustomer::create([
            'id' => $customer->getCustomerId()->__toString(),
            'email' => $customer->getEmail(),
            'name' => $customer->getCustomerName(),
            'password' => $customer->getPassword(),
            'phone' => $customer->getPhone(),
            'is_active' => $customer->getIsActive(),
        ]);

        if(!$result) {
            throw new InvalidArgumentException();
        }

        return $customer->getCustomerId();
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $entities = ModelCustomer::paginate(PaginationConst::PAGINATE_ROW);

        /** @var \App\Bundle\Admin\Domain\Model\User[] $result */
        $customers = [];
        foreach ($entities as $entity) {
            $customer = new Customer(
                new CustomerId($entity->id),
                $entity->name,
                $entity->email
            );
            $customer->setPhone($entity->phone);
            $customer->setIsActive($entity->is_active);

            $customers[] = $customer;
        }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

        return [$customers, $pagination];
    }

    /**
     * @inheritDoc
     */
    public function findById(CustomerId $customerId): ?Customer
    {
        $entity = ModelCustomer::find($customerId->__toString());
        if (!$entity) {
            return null;
        }

        $customer = new Customer(
            $customerId,
            $entity->name,
            $entity->email,
        );
        $customer->setPhone($entity->phone);
        $customer->setIsActive($entity->is_active);

        return $customer;
    }

    /**
     * @inheritDoc
     */
    public function update(Customer $customer): CustomerId
    {
        $entity = ModelCustomer::find($customer->getCustomerId()->__toString());
        $data = [
            'name' => $customer->getCustomerName(),
            'phone' => $customer->getPhone(),
            'is_active' => $customer->getIsActive(),
        ];

        if ($customer->getPassword()) {
            $data['password'] = $customer->getPassword();
        }

        $result = $entity->update($data);
        if(!$result) {
            throw new InvalidArgumentException();
        }

        return $customer->getCustomerId();
    }

    public function delete(CustomerId $customerId): bool
    {
        $entity = ModelCustomer::find($customerId->__toString());
        $result = $entity->delete();

        if (!$result) {
            throw new Exception();
        }

        return true;
    }

    /**
     * @param string $email
     * @param CustomerId|null $customerId
     * @return bool
     */
    public function checkExistingEmail(string $email, ?CustomerId $customerId = null): bool
    {
        $entities = ModelCustomer::where('email' , $email)->get();

        if ($entities->isEmpty()) {
            return false;
        }
        if (is_null($customerId)) {
            return true;
        }

        return !$entities->contains(ModelCustomer::find($customerId->__toString()));
    }
}
