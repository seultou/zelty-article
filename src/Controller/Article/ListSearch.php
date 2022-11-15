<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Model\Article\Article;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function var_dump;

class ListSearch extends AbstractFOSRestController
{
    public function __invoke(ManagerRegistry $registry, Request $request): Response
    {
        return $this->handleView($this->view(
            $registry->getRepository(Article::class)->fromRequest($request),
                Response::HTTP_OK
        ));
    }
}
