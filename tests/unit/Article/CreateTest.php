<?php

declare(strict_types=1);

namespace App\Tests\unit\Article;

use App\Tests\unit\BaseTest;
use function Safe\json_decode;

class CreateTest extends BaseTest
{
    public const CREATED_TITLE = 'My best article ever!';
    public const CREATED_CONTENTS = 'Best contents ever!';

    public function testCreateArticle(): string
    {
        $response = $this->withToken()->sendRequest(
            '/api/article',
            [
                'title' => self::CREATED_TITLE,
                'contents' => self::CREATED_CONTENTS,
                'publishingDate' => '2022-12-25 10:00:00',
                'status' => 'DRAFT',
            ]
        );
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayNotHasKey('errorMessage', $contents);
        self::assertArrayHasKey('title', $contents);
        self::assertArrayHasKey('contents', $contents);
        self::assertArrayHasKey('author', $contents);

        return $contents['id'];
    }

    public function testCreateArticleWithDateInPast(): void
    {
        $response = $this->withToken()->sendRequest(
            '/api/article',
            [
                'title' => self::CREATED_TITLE,
                'contents' => self::CREATED_CONTENTS,
                'publishingDate' => '2021-12-25 10:00:00',
                'status' => 'DRAFT',
            ]
        );
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('code', $contents);
        self::assertArrayHasKey('errorMessage', $contents);
    }

    public function testCreateArticleWithWrongStatus(): void
    {
        $response = $this->withToken()->sendRequest(
            '/api/article',
            [
                'title' => self::CREATED_TITLE,
                'contents' => self::CREATED_CONTENTS,
                'publishingDate' => '2022-12-25 10:00:00',
                'status' => 'DELETED',
            ]
        );
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('code', $contents);
        self::assertArrayHasKey('errorMessage', $contents);
    }

    public function testCreateArticleWithWrongDateAndStatus(): void
    {
        $response = $this->withToken()->sendRequest(
            '/api/article',
            [
                'title' => self::CREATED_TITLE,
                'contents' => self::CREATED_CONTENTS,
                'publishingDate' => '2021-12-25 10:00:00',
                'status' => 'DELETED',
            ]
        );
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('code', $contents);
        self::assertArrayHasKey('errorMessages', $contents);
    }
}
