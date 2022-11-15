<?php

declare(strict_types=1);

namespace App\Model\Article;

use App\Common\Validator;
use App\Model\Author\Author;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use function var_dump;

class Article
{
    private const TITLE_MAX_LENGTH = 128;

    private Uuid $id;

    private string $status;

    public function __construct(
        private string $title,
        private string $contents,
        private Author $author,
        private ?DateTimeImmutable $publishingDate,
        Status $status,
    ) {
        $this->id = Uuid::v4();
        Validator::stringNotEmpty($title);
        Validator::maxLength($title, self::TITLE_MAX_LENGTH);

        $this->status = $status->value;
    }

    public static function fromHttpRequest(Request $request, UserInterface $author): self
    {
        return new self(
            $request->request->get('title'),
            $request->request->get('contents'),
            $author,
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->request->get('publishingDate')) ?: null,
            Status::tryFrom(mb_strtoupper($request->request->get('status')))
        );
    }

    public function updateFromRequest(Request $request): self
    {
        $rq = $request->request;
        $this->title = $rq->has('title') ? $rq->get('title') : $this->title();
        $this->contents = $rq->has('contents') ? $rq->get('contents') : $this->contents();
        $this->publishingDate = $rq->has('publishingDate')
            ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $rq->get('publishingDate'))
            : $this->publishingDate();
        $this->status = Status::tryFrom(mb_strtoupper($rq->has('status') ? $rq->get('status') : $this->status()))->value;

        return $this;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function contents(): string
    {
        return $this->contents;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function publishingDate(): ?DateTimeImmutable
    {
        return $this->publishingDate;
    }

    public function status(): Status
    {
        return Status::from($this->status);
    }
}
