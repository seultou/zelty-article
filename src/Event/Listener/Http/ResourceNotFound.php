<?php

declare(strict_types=1);

namespace App\Event\Listener\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function get_class;
use function Safe\json_encode;

class ResourceNotFound
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (get_class($event->getThrowable()) !== NotFoundHttpException::class) {
            return;
        }

        $response = new Response();
        $response->setContent(
            json_encode([
                'code' => Response::HTTP_NOT_FOUND,
                'errorMessage' => 'Resource not found',
            ])
        );
        $response->setStatusCode(Response::HTTP_NOT_FOUND);

        $event->setResponse($response);
    }
}
