<?php

declare(strict_types=1);

namespace App\Validator\Article\Constraint;

use Symfony\Component\Validator\Constraint;

class PublishingDateIsNotInPast extends Constraint
{
    public string $message = 'The date "{{ date }}" is in the past.';

    public string $mode = 'strict';

    public function validatedBy()
    {
        return \App\Validator\Article\Validator\PublishingDateIsNotInPast::class;
    }
}
