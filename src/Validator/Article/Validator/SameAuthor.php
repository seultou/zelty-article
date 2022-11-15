<?php

declare(strict_types=1);

namespace App\Validator\Article\Validator;

use App\Model;
use App\Validator\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SameAuthor extends ConstraintValidator
{
    public int $statusCode = Response::HTTP_FORBIDDEN;

    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Model\Article\Article) {
            throw new UnexpectedTypeException($value, Model\Article\Article::class);
        }

        if (!$constraint instanceof Article\Constraint\SameAuthor) {
            throw new UnexpectedTypeException($constraint, Article\Constraint\SameAuthor::class);
        }

        if ($value->author() !== $this->tokenStorage->getToken()->getUser()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode('403')
                ->addViolation();
        }
    }
}

