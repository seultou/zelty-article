<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Model\Article\Article;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Delete
{
    public function __invoke(Article $article): Response
    {
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

}
