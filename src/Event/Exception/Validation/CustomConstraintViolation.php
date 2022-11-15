<?php

declare(strict_types=1);

namespace App\Event\Exception\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface CustomConstraintViolation
{
    public static function create(ConstraintViolationListInterface $validation): self;

    public function messages(): array;

    public function count(): int;

    public function statusCode(): int;
}
