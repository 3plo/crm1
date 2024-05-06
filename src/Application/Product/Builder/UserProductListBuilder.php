<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.2024
 * Time: 1:20
 */

namespace App\Application\Product\Builder;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\User\Enum\Role;
use App\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserProductListBuilder
{
    public function __construct(
        private readonly TokenStorageInterface  $tokenStorage,
        private readonly ProductRepository      $productRepository,
    ) {
    }

    /**
     * @return Product[]
     */
    public function build(): array
    {
        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();

        return
            true === in_array(Role::RoleAdmin->value, $user->getRoles() , true) ?
                $this->productRepository->findAll() :
                $this->productRepository->findByLocationList($user->getLocationAccessList());
    }
}
