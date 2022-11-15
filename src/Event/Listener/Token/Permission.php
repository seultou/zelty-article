<?php

declare(strict_types=1);

namespace App\Event\Listener\Token;

use App\Event\Exception\Token\PermissionFailed;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use function get_class;
use function Safe\json_encode;

class Permission
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (get_class($event->getThrowable()) !== PermissionFailed::class) {
            return;
        }

        $response = new Response();
        $response->setContent(
            json_encode([
                'code' => Response::HTTP_FORBIDDEN,
                'errorMessage' => $event->getThrowable()->getMessage(),
            ])
        );
        $response->setStatusCode(Response::HTTP_FORBIDDEN);

        $event->setResponse($response);
    }

}
