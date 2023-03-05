<?php

namespace App\Bundle\Admin\Infrastructure;

use App\Bundle\Admin\Domain\Model\Customer;
use App\Bundle\Admin\Domain\Model\CustomerId;
use App\Bundle\Admin\Domain\Model\ICustomerRepository;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\Common\Domain\Model\Pagination;
use App\Bundle\ProductBundle\Domain\Model\CustomerCriteria;
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
            'address' => $customer->getAddress(),
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
    public function findAll(CustomerCriteria $criteria): array
    {
        $keyword = $criteria->getKeyword();
        $conditions = [];
        if ($criteria->getKeyword()) {
            $conditions[] = ['name', 'like', "%$keyword%"];
        }
        $entities = ModelCustomer::where($conditions)->paginate(PaginationConst::PAGINATE_ROW);

        /** @var \App\Bundle\Admin\Domain\Model\User[] $result */
        $customers = [];
        foreach ($entities as $entity) {
            $customer = new Customer(
                new CustomerId($entity->id),
                $entity->name,
                $entity->email
            );
            $customer->setPhone($entity->phone);
            $customer->setAddress($entity->address);
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
        $customer->setAddress($entity->address);
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
            'address' => $customer->getAddress(),
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
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
    public function findAllByKeyword(string $keyword): array
    {
        $entities = ModelCustomer::where([['name', 'like', "%$keyword%"]])->get();

        /** @var \App\Bundle\Admin\Domain\Model\User[] $result */
        $customers = [];
        foreach ($entities as $entity) {
            $customer = new Customer(
                new CustomerId($entity->id),
                $entity->name,
                $entity->email
            );
            $customer->setPhone($entity->phone);
            $customer->setAddress($entity->phone);
            $customer->setIsActive($entity->is_active);

            $customers[] = $customer;
        }

        return $customers;
    }


    /**
     * @inheritDoc
     */
    public function findAllNotPaginate(CustomerCriteria $criteria): array
    {
        $keyword = $criteria->getKeyword();
        $conditions = [['is_active', '=', true]];
        if ($criteria->getKeyword()) {
            $conditions[] = ['name', 'like', "%$keyword%"];
        }
        $entities = ModelCustomer::where($conditions)->get();

        /** @var \App\Bundle\Admin\Domain\Model\User[] $result */
        $customers = [];
        foreach ($entities as $entity) {
            $customer = new Customer(
                new CustomerId($entity->id),
                $entity->name,
                $entity->email
            );
            $customer->setPhone($entity->phone);
            $customer->setAddress($entity->address);
            $customer->setIsActive($entity->is_active);

            $customers[] = $customer;
        }

        return $customers;
    }
}
