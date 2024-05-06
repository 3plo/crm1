<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.2024
 * Time: 19:24
 */

namespace App\View\Access\Checker;

use App\Domain\User\Enum\Role;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LocationChecker
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @param string[] $locationIdList
     */
    public function checkAccessToAllLocationList(array $locationIdList): void
    {
        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        if (null === $user) {
            throw new \RuntimeException('User not exist');
        }

        if (true === in_array(Role::RoleAdmin->value, $user->getRoles(), true)) {
            return;
        }

        $locationAccessList = $user->getLocationAccessList();
        if ($locationIdList === array_intersect($locationAccessList, $locationIdList)) {
            return;
        }

        throw new AccessDeniedException('No corresponding rights');
    }

    /**
     * @param string[] $locationIdList
     */
    public function checkAccessToAnyLocationList(array $locationIdList): void
    {
        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        if (null === $user) {
            throw new \RuntimeException('User not exist');
        }

        if (true === in_array(Role::RoleAdmin->value, $user->getRoles(), true)) {
            return;
        }

        $locationAccessList = $user->getLocationAccessList();
        if ([] !== array_intersect($locationAccessList, $locationIdList)) {
            return;
        }

        throw new AccessDeniedException('No corresponding rights');
    }
}
