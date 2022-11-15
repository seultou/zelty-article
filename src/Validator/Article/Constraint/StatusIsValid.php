<?php

declare(strict_types=1);

namespace App\Validator\Article\Constraint;

use Symfony\Component\Validator\Constraint;

class StatusIsValid extends Constraint
{
    public string $message = 'The status "{{ status }}" is not valid at creation or update';

    public string $mode = 'strict';

    public function validatedBy()
    {
        return \App\Validator\Article\Validator\StatusIsValid::class;
    }
}
