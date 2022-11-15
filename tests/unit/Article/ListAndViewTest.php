<?php

declare(strict_types=1);

namespace App\Tests\unit\Article;

use App\Tests\unit\BaseTest;
use function Safe\json_decode;

class ListAndViewTest extends BaseTest
{
    public function testListArticles()
    {
        $response = $this->withToken()->sendRequest('/api/article');
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayNotHasKey('errorMessage', $contents);
        self::assertNotCount(0, $contents);
    }

    public function testViewMyArticle(): void
    {
        $response = $this->withToken()->sendRequest('/api/article/' . $this->anyArticle()->id());
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayNotHasKey('errorMessage', $contents);
        self::assertArrayHasKey('id', $contents);
        self::assertArrayHasKey('title', $contents);
        self::assertArrayHasKey('contents', $contents);
        self::assertArrayHasKey('author', $contents);
        self::assertArrayHasKey('status', $contents);
    }

    public function testViewNotOwnedArticle(): void
    {
        $response = $this->withToken()->sendRequest('/api/article/' . $this->anyArticle(false)->id());
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(403, $response->getStatusCode());
        self::assertArrayHasKey('code', $contents);
        self::assertArrayHasKey('errorMessage', $contents);
        self::assertEquals('You are not allowed to view this article', $contents['errorMessage']);
    }

    /**
     * @depends App\Tests\unit\Article\CreateTest::testCreateArticle
     */
    public function testViewJustCreatedArticle(string $id)
    {
        $response = $this->withToken()->sendRequest('/api/article/' . $id);
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertArrayNotHasKey('errorMessage', $contents);
        self::assertArrayHasKey('id', $contents);
        self::assertArrayHasKey('title', $contents);
        self::assertArrayHasKey('contents', $contents);
        self::assertArrayHasKey('author', $contents);
        self::assertArrayHasKey('status', $contents);
    }

    /**
     * @depends App\Tests\unit\Article\UpdateTest::testUpdateArticle
     */
    public function testViewJustUpdatedArticle(?string $id)
    {
        $response = $this->withToken()->sendRequest('/api/article/' . $id);
        $contents = json_decode($response->getBody()->getContents(), true);

        self::assertArrayNotHasKey('errorMessage', $contents);
        self::assertArrayHasKey('id', $contents);
        self::assertEquals(UpdateTest::UPDATED_TITLE, $contents['title']);
        self::assertEquals(UpdateTest::UPDATED_PUBLISHING_DATE, $contents['publishingDate']);
        self::assertArrayHasKey('title', $contents);
        self::assertArrayHasKey('contents', $contents);
        self::assertArrayHasKey('author', $contents);
        self::assertArrayHasKey('status', $contents);
    }
}
