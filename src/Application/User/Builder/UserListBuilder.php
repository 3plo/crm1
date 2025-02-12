<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.2024
 * Time: 1:20
 */

namespace App\Application\User\Builder;

use App\Domain\User\Enum\Role;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class UserListBuilder
{
    public function __construct(
        private TokenStorageInterface  $tokenStorage,
        private UserRepository         $userRepository,
    ) {
    }

    /**
     * @return User[]
     */
    public function build(): array
    {
        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();

        return
            true === in_array(Role::RoleAdmin->value, $user->getRoles() , true) ?
                $this->userRepository->findAll() :
                [$user];
    }
}
