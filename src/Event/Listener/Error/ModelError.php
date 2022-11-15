<?php

declare(strict_types=1);

namespace App\Event\Listener\Error;

use App\Event\Error\ModelCreate;
use App\Event\Error\ModelUpdate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use function get_class;
use function in_array;
use function Safe\json_encode;

class ModelError
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (!in_array(get_class($event->getThrowable()), [ModelCreate::class, ModelUpdate::class])) {
            return;
        }

        $response = new Response();
        $response->setContent(
            json_encode([
                'code' => Response::HTTP_BAD_REQUEST,
                'errorMessage' => $event->getThrowable()->getMessage(),
            ])
        );
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        $event->setResponse($response);
    }
}
