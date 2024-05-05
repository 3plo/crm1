<?php
/**
 * Created by PhpStorm.
 * Date: 30.04.2024
 * Time: 20:12
 */

namespace App\View\Access\EventSubscriber;

use App\Domain\User\Enum\Role;
use App\Domain\User\User;
use App\View\Access\Attribute\LocationAccess;
use App\View\Request\LocationAccessInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LocationAccessListener implements EventSubscriberInterface
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
        /** @var LocationAccess[]|null $attributes */
        $attributes = $event->getAttributes()[LocationAccess::class] ?? null;
        if (null === $attributes) {
            return;
        }

        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        if (null === $user) {
            throw new \RuntimeException('User not exist');
        }

        if (true === in_array(Role::ROLE_ADMIN->value, $user->getRoles(), true)) {
            return;
        }

        $request = $event->getArguments()[0] ?? null;
        if (false === $request instanceof LocationAccessInterface) {
            return;
        }

        $locationAccessList = $user->getLocationAccessList();
        $requestLocationList = $request->getLocationList();
        if ($requestLocationList === array_intersect($locationAccessList, $requestLocationList)) {
            return;
        }

        throw new AccessDeniedException('No corresponding rights');
    }
}
