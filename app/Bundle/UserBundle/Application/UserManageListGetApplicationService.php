<?php
namespace App\Bundle\UserBundle\Application;

use App\Bundle\Common\Application\PaginationResult;
use App\Bundle\UserBundle\Domain\Model\IOrganizationRepository;
use App\Bundle\UserBundle\Domain\Model\IUserRepository;

final class UserManageListGetApplicationService
{
    /**
     * @var \App\Bundle\UserBundle\Domain\Model\IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @var \App\Bundle\UserBundle\Domain\Model\IOrganizationRepository
     */
    private IOrganizationRepository $organizationRepository;

    /**
     * @param \App\Bundle\UserBundle\Domain\Model\IUserRepository $userRepository userRepository
     * @param \App\Bundle\UserBundle\Domain\Model\IOrganizationRepository $organizationRepository organizationRepository
     */
    public function __construct(
        IUserRepository $userRepository,
        IOrganizationRepository $organizationRepository
    ) {
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @param \App\Bundle\UserBundle\Application\UserManageListGetCommand $command command
     * @return \App\Bundle\UserBundle\Application\UserManageListGetResult
     */
    public function handle(UserManageListGetCommand $command): UserManageListGetResult
    {
        [$users, $pagination] = $this->userRepository->findAll();

        /** @var \App\Bundle\UserBundle\Application\UserManageResult[] $userManageResult */
        $userManageResult = [];

        foreach ($users as $user) {
            $organization = $this->organizationRepository->findById($user->getOrganizationId());
            $userManageResult[] = new UserManageResult(
                $user->getUserId()->getValue(),
                $user->getEmail(),
                $user->getFirstName().' '.$user->getLastName(),
                $organization->getName(),
                $user->getUserType()->getValue(),
                (bool)$user->getIsActive(),
                $user->getRegisterDate(),
                $user->getLoginLastDate()
            );
        }
        $paginationResult = new PaginationResult(
            $pagination->getTotalPages(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
        );

        return new UserManageListGetResult(
            $userManageResult,
            $paginationResult
        );
    }
}
