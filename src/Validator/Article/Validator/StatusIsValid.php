<?php

declare(strict_types=1);

namespace App\Validator\Article\Validator;

use App\Common\DatetimeImmutable;
use App\Model\Article\Status;
use App\Validator\Article;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use function in_array;

class StatusIsValid extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Article\Constraint\StatusIsValid) {
            throw new UnexpectedTypeException($constraint, Article\Constraint\StatusIsValid::class);
        }

        if (!in_array($value, [Status::Draft->value, Status::Published->value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ status }}', $value)
                ->addViolation();
        }
    }
}
