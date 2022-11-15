<?php

declare(strict_types=1);

namespace App\Tests\unit\Article;

use App\Model\Article\Status;
use App\Tests\unit\BaseTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use function Safe\json_decode;
use function var_dump;

class UpdateTest extends BaseTest
{
    public const UPDATED_TITLE = 'Wrong title, lets update it!';
    public const UPDATED_PUBLISHING_DATE = '2023-01-01 10:00:00';

    public function testUpdateArticle(): string
    {
        $response = $this->withToken()->sendRequest(
            '/api/article/' . $this->anyArticle()->id(),
            [
                'title' => self::UPDATED_TITLE,
                'status' => Status::Published->value,
                'publishingDate' => self::UPDATED_PUBLISHING_DATE,
            ],
            Request::METHOD_PUT
        );
        $contents = json_decode($response->getBody()->getContents(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(self::UPDATED_TITLE, $contents['title']);

        return $this->anyArticle()?->id()?->toRfc4122();
    }

    public function testUpdateNotExistingArticle(): void
    {
        $response = $this->withToken()->sendRequest(
            '/api/article/' . Uuid::v4()->toRfc4122(),
            [
                'title' => self::UPDATED_TITLE,
                'status' => Status::Published->value,
                'publishingDate' => self::UPDATED_PUBLISHING_DATE,
            ],
            Request::METHOD_PUT
        );
        $contents = json_decode($response->getBody()->getContents(), true);
        self::assertEquals(404, $response->getStatusCode());
        self::assertArrayHasKey('code', $contents);
        self::assertArrayHasKey('errorMessage', $contents);
    }

    public function testUpdateNotOwnedArticle(): void
    {
        $response = $this->withToken()->sendRequest(
            '/api/article/' . $this->anyArticle(false)->id(),
            [
                'title' => self::UPDATED_TITLE,
                'status' => Status::Published->value,
                'publishingDate' => self::UPDATED_PUBLISHING_DATE,
            ],
            Request::METHOD_PUT
        );
        $contents = json_decode($response->getBody()->getContents(), true);
        self::assertEquals(403, $response->getStatusCode());
        self::assertEquals('Your are not the author of the article', $contents['errorMessage']);
        self::assertArrayHasKey('code', $contents);
        self::assertArrayHasKey('errorMessage', $contents);
    }
}
