<?php

declare(strict_types=1);

namespace App\Event\Exception\Validation;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use function intval;

class ViolatedArticle extends Exception implements CustomConstraintViolation
{
    private function __construct(private array $messages, private int $count, private int $statusCode)
    {
        parent::__construct('');
    }

    public static function create(ConstraintViolationListInterface $validation): self
    {
        $messages = [];
        $statusCode = Response::HTTP_BAD_REQUEST;
        foreach ($validation as $violation) {
            $messages[] = $violation->getMessage();
            if ($violation->getCode() !== null) {
                $statusCode = intval($violation->getCode());
                continue;
            }
        }

        return new self($messages, $validation->count(), $statusCode);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function messages(): array
    {
        return $this->messages;
    }

    public function count(): int
    {
        return $this->count;
    }
}
