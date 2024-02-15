<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 23:01
 */

namespace App\Infrastructure\Exception\ExceptionSubscriber;

use App\Infrastructure\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelExceptionSubscriber implements EventSubscriberInterface
{
    #[\Override] public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (
            true === $exception instanceof ValidationException
        ) {
            $event->setResponse(
                new JsonResponse($exception->toArray()),
            );
        }

        $event->stopPropagation();
    }
}
