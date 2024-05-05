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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class KernelExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RouterInterface $router
    ) {
    }

    #[\Override] public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (true === $exception instanceof ValidationException) {
            $this->handleValidationException($exception, $event);
        }

        if (true === $exception instanceof AccessDeniedException) {
            $this->handleAccessDeniedException($exception, $event);
        }
    }

    /**
     * @param \Throwable $exception
     * @param ExceptionEvent $event
     */
    public function handleValidationException(\Throwable $exception, ExceptionEvent $event): void
    {
        $event->setResponse(new JsonResponse($exception->toArray()));
        $event->stopPropagation();
    }

    /**
     * @param \Throwable $exception
     * @param ExceptionEvent $event
     */
    public function handleAccessDeniedException(\Throwable $exception, ExceptionEvent $event): void
    {
        $event->setResponse(new RedirectResponse('/error/403'));
        $event->stopPropagation();
    }
}
