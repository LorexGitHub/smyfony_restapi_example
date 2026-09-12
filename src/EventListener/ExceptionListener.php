<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        $path = $request->getPathInfo();

        if(!str_starts_with($path, '/api')) {
            return;
        }

        $exeption = $event->getThrowable();

        $response = new JsonResponse([
            'error' => $exeption->getMessage()
        ]);

        $event->setResponse($response);
    }
}
