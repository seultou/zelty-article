<?php

declare(strict_types=1);

namespace App\Repository\SQLFilter;

use App\Model\Article\Article;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use function sprintf;
use function var_dump;

class FetchWithoutDeleted extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($targetEntity->name !== Article::class) {
            return '';
        }

        return sprintf('%s.status != "DELETED"', $targetTableAlias);
    }
}
