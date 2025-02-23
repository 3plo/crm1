<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.2024
 * Time: 1:23
 */

namespace App\Application\Location\Builder;

use App\Domain\Location\Location;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\User\Enum\Role;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class UserLocationListBuilder
{
    public function __construct(
        private TokenStorageInterface  $tokenStorage,
        private LocationRepository     $locationRepository,
    ) {
    }

    /**
     * @return Location[]
     */
    public function build(): array
    {
        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();

        return
            true === in_array(Role::RoleAdmin->value, $user->getRoles() , true) ?
                $this->locationRepository->findAll() :
                $this->locationRepository->findBy(['id' => $user->getLocationAccessList()]);
    }
}
