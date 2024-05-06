<?php
/**
 * Created by PhpStorm.
 * Date: 25.04.2024
 * Time: 20:16
 */

namespace App\View\Access\EventSubscriber;

use App\Domain\User\Enum\Role;
use App\Domain\User\User;
use App\View\Access\Attribute\ActionAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ActionAccessListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    #[\Override] public static function getSubscribedEvents()
    {
        return [KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArguments', 10]];
    }

    /**
     * @throws AccessDeniedException
     * @throws \RuntimeException
     */
    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        /** @var ActionAccess[]|null $attributes */
        $attributes = $event->getAttributes()[ActionAccess::class] ?? null;
        if (null === $attributes) {
            return;
        }

        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        if (null === $user) {
            throw new \RuntimeException('User not exist');
        }

        if (true === in_array(Role::RoleAdmin->value, $user->getRoles(), true)) {
            return;
        }

        if ([] === $attributes) {
            return;
        }

        $userAccessRights = $user->getAccessList();
        foreach ($attributes as $attribute) {
            $attributeRights = $attribute->getActionList();
            if ([] !== array_intersect($attributeRights, $userAccessRights)) {
                return;
            }
        }

        throw new AccessDeniedException('No corresponding rights');
    }
}
