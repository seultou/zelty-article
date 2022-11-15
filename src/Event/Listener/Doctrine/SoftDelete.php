<?php

declare(strict_types=1);

namespace App\Event\Listener\Doctrine;

use Doctrine\ORM\Event\PreFlushEventArgs;

class SoftDelete
{
    public function preFlush(PreFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        foreach ($em->getUnitOfWork()->getScheduledEntityDeletions() as $entity) {
            $em->merge($entity);
            $em->persist($entity);
        }
    }
}
