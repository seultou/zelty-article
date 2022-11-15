<?php

declare(strict_types=1);

namespace App\Validator\Article\Constraint;

use Symfony\Component\Validator\Constraint;

class SameAuthor extends Constraint
{
    public string $message = 'Your are not the author of the article';

    public function validatedBy()
    {
        return \App\Validator\Article\Validator\SameAuthor::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
