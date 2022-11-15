<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Model\Article\Status;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Field extends AbstractFOSRestController
{
    public function __invoke(): Response
    {
        return new JsonResponse([
            'id' => 'UUID',
            'title' => 'string (128 characters)',
            'contents' => 'text',
            'publishingDate' => ['format' => 'Y-m-d H:i:s'],
            'status' => Status::cases()
        ]);
    }
}
