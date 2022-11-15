<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Event\Exception\Doctrine\SQLException;
use App\Model\Article\Article;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class UpdateFromRequest
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
    }

    public function __invoke(Article $updated): Article
    {
        $manager = $this->registry->getManager();
        $manager->persist($updated);
        try {
            $manager->flush();
        } catch (Exception $exception) {
            throw SQLException::fromUpdate($exception);
        }

        return $updated;
    }
}
