<?php
namespace App\Bundle\UserBundle\Infrastructure;

use App\Bundle\Common\Constants\DateTimeConst;
use App\Bundle\Common\Constants\PaginationConst;
use App\Bundle\UserBundle\Domain\Model\GenderType;
use App\Bundle\UserBundle\Domain\Model\IUserRepository;
use App\Bundle\UserBundle\Domain\Model\OrganizationId;
use App\Bundle\UserBundle\Domain\Model\Pagination;
use App\Bundle\UserBundle\Domain\Model\User;
use App\Bundle\UserBundle\Domain\Model\UserId;
use App\Bundle\UserBundle\Domain\Model\UserRole;
use App\Bundle\UserBundle\Domain\Model\UserType;
use App\Bundle\UserBundle\Domain\Model\UserWorkingGroup;
use App\Models\User as ModelUser;

class UserRepository implements IUserRepository
{
    /**
     * @inheritDoc
     */
    public function createUser(User $user): UserId
    {
        $result = ModelUser::create([
    		'active' => $user->getIsActive(),
        	'creation_time' => time(),
        	'email' => $user->getEmail(),
        	'email_verified_at' => null,
        	'first_name' => $user->getFirstName(),
        	'last_name' => $user->getLastName(),
        	'first_name_furigana' => $user->getFirstNameFurigana(),
        	'last_name_furigana' => $user->getLastNameFurigana(),
            'password' => $user->getPassword(),
            'gender' => $user->getGenderType()->getValue(),
            'last_login_time' => time(),
            'locale_key_spoken' => null,
            'receive_space_search_form_notification' => $user->getRequestNotification(),
            'subscribe_mail_magazine' => $user->getReceiveEmail(),
            'organization_id' => $user->getOrganizationId()->getValue(),
            'roles' => $user->getUserRoleWithJson(),
            'working_groups' => $user->getUserWorkingGroupsWithJson(),
            'type' => $user->getUserType()->getValue(),
            'remember_token' => null,
            'locale_key' => 'ja',
            'login_count_current_month' => null,
            'login_trigger' => 0,
            'recovery_token' => null,
    	]);

        return new UserId($result->id);
    }

    /**
     * @inheritDoc
     */
    public function remove(UserId $userId): bool
    {
       // TO DO
       return true;
    }

    /**
     * @inheritDoc
     */
    public function findById(UserId $userId): ?User
    {
        $entity = ModelUser::find($userId->getValue());
        if (!$entity) {
            return null;
        }
        $userRoles = [];
        foreach (json_decode($entity->roles) as $userRole) {
            $userRoles[] = UserRole::fromValue($userRole);
        }
        $userWorkingGroups = [];
        foreach (json_decode($entity->working_groups) as $userWorkingGroup) {
            $userWorkingGroups[] = UserWorkingGroup::fromValue($userWorkingGroup);
        }

        $users = new User(
            new UserId($entity->id),
            new OrganizationId($entity->organization_id),
            UserType::fromValue($entity->type),
            (bool)$entity->active,
            $entity->first_name,
            $entity->last_name,
            $entity->first_name_furigana,
            $entity->last_name_furigana,
            [],
            GenderType::fromValue($entity->gender),
            $entity->email,
            (bool)$entity->receive_space_search_form_notification,
            (bool)$entity->subscribe_mail_magazine,
            $userRoles,
            $userWorkingGroups,
            $entity->password,
            date(DateTimeConst::FORMAT, $entity->creation_time),
            $entity->last_login_time ? date(DateTimeConst::FORMAT, $entity->last_login_time) : null,
        );

        return $users;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
       $entities = ModelUser::paginate(PaginationConst::PAGINATE_ROW);

       /** @var \App\Bundle\UserBundle\Domain\Model\User[] $result */
       $users = [];

       foreach ($entities as $entity) {
           $userRoles = [];
           foreach (json_decode($entity->roles) as $userRole) {
               $userRoles[] = UserRole::fromValue($userRole);
           }
           $userWorkingGroups = [];
           foreach (json_decode($entity->working_groups) as $userWorkingGroup) {
               $userWorkingGroups[] = UserWorkingGroup::fromValue($userWorkingGroup);
           }
           $users[] = new User(
               new UserId($entity->id),
               new OrganizationId($entity->organization_id),
               UserType::fromValue($entity->type),
               (bool)$entity->active,
               $entity->first_name,
               $entity->last_name,
               $entity->first_name_furigana,
               $entity->last_name_furigana,
               [],
               GenderType::fromValue($entity->gender),
               $entity->email,
               (bool)$entity->receive_space_search_form_notification,
               (bool)$entity->subscribe_mail_magazine,
               $userRoles,
               $userWorkingGroups,
               $entity->password,
               $entity->creation_time,
               $entity->last_login_time
           );
       }

        $pagination = new Pagination(
            $entities->lastPage(),
            $entities->perPage(),
            $entities->currentPage()
        );

       return [
           $users,
           $pagination
       ];
    }

    /**
     * @inheritDoc
     */
    public function updateUser(User $user): UserId
    {
        $entity = ModelUser::find($user->getUserId()->getValue());

        $data = [
            'active' => $user->getIsActive(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'first_name_furigana' => $user->getFirstNameFurigana(),
            'last_name_furigana' => $user->getLastNameFurigana(),
            'gender' => $user->getGenderType()->getValue(),
            'receive_space_search_form_notification' => $user->getRequestNotification(),
            'subscribe_mail_magazine' => $user->getReceiveEmail(),
            'organization_id' => $user->getOrganizationId()->getValue(),
            'roles' => $user->getUserRoleWithJson(),
            'working_groups' => $user->getUserWorkingGroupsWithJson(),
            'type' => $user->getUserType()->getValue(),
        ];
        if ($user->getPassword()) {
            $data['password'] = $user->getPassword();
        }
        $result = $entity->update($data);

        return new UserId($entity->id);
    }
}
