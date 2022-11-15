<?php

declare(strict_types=1);

namespace App\Validator\Article\Validator;

use App\Common\DatetimeImmutable;
use App\Validator\Article;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PublishingDateIsNotInPast extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Article\Constraint\PublishingDateIsNotInPast) {
            throw new UnexpectedTypeException($constraint, Article\Constraint\PublishingDateIsNotInPast::class);
        }

        if ($value < DatetimeImmutable::create('now')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $value->format(DatetimeImmutable::FORMAT))
                ->addViolation();
        }
    }
}
