<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Event\Error\ModelCreate;
use App\Event\Exception\Doctrine\SQLException;
use App\Model\Article\Article;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TypeError;

class CreateFromRequest
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function __invoke(Request $request): Article
    {
        $manager = $this->registry->getManager();
        try {
            $article = Article::fromHttpRequest($request, $this->tokenStorage->getToken()->getUser());
        } catch (TypeError $error) {
            throw new ModelCreate('Error while creating an Article');
        }
        $manager->persist($article);

        try {
            $manager->flush();
        } catch (Exception $exception) {
            throw SQLException::fromCreation($exception);
        }

        return $article;
    }
}
