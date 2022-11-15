<?php

declare(strict_types=1);

namespace App\Event\Listener\Validation;

use App\Event\Exception\Validation\CustomConstraintViolation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use function Safe\json_encode;

class ConstraintViolation
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (!$event->getThrowable() instanceof CustomConstraintViolation) {
            return;
        }
        /** @var CustomConstraintViolation $violation */
        $violation = $event->getThrowable();

        $response = new Response();
        $response->setContent(
            json_encode([
                'code' => $violation->statusCode(),
                'errorMessage' . ($violation->count() > 1 ? 's' : '') => $violation->count() === 1
                        ? $violation->messages()[0]
                        : $violation->messages(),
            ])
        );
        $response->setStatusCode($violation->statusCode());

        $event->setResponse($response);
    }
}
