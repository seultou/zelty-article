<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Event\Exception\Token\PermissionFailed;
use App\Model\Article\Article;
use App\Model\Article\Status;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use function var_dump;

class Read extends AbstractFOSRestController
{
    public function __invoke(Article $article): Response
    {
        if ($article->status() !== Status::Published) {
            if ($this->getUser() !== $article->author()) {
                throw new PermissionFailed('You are not allowed to view this article');
            }
        }

        return $this->handleView($this->view($article, Response::HTTP_OK));
    }
}
