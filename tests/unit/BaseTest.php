<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Model\Article\Article;
use App\Model\Author\Author;
use Doctrine\ORM\Query\Expr\Join;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use function current;
use function Safe\json_decode;

class BaseTest extends WebTestCase
{
    protected const USERNAME_TEST = 'admin';
    protected const PASSWORD_TEST = 'toto42';

    protected Client $client;

    protected string $token;

    private bool $withToken = false;

    public static function setUpBeforeClass(): void
    {
        static::bootKernel([
            'environment' => 'test',
            'debug' => true,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client([
            'base_uri' => $_ENV['DOCKER_BASE_URL'] ?? $_ENV['BASE_URL'],
        ]);
        $this->withToken = false;
        $this->token = '';
    }

    protected function tearDown(): void { }

    protected function getService(string $id): ?object
    {
        return static::$kernel->getContainer()->get($id);
    }

    protected function token(): string
    {
        return json_decode($this->sendRequest(
            '/api/login_check',
            [
                'username' => self::USERNAME_TEST,
                'password' => self::PASSWORD_TEST
            ]
        )->getBody()->getContents(),  true)['token'];
    }

    protected function user(): UserInterface
    {
        return $this
            ->getService('doctrine')
            ->getRepository(Author::class)
            ->findOneBy(['username' => self::USERNAME_TEST])
        ;
    }

    protected function withToken(): self
    {
        $this->withToken = true;
        $this->token = $this->token();

        return $this;
    }

    protected function sendRequest(string $uri, array $body = [], string $method = 'POST'): Response
    {
        $headers = ['http_errors' => false];
        $headers['headers'] = ['Content-Type' => 'application/json'];
        $uri = $uri . '?env=test';
        if ($this->withToken === true) {
            $headers['headers']['Authorization'] = 'Bearer ' . $this->token;
        }

        if (!empty($body)) {
            $headers[RequestOptions::JSON] = $body;
            if ($method !== Request::METHOD_POST) {
                return $this->client->request($method, $uri, $headers);
            }
            return $this->client->post($uri, $headers);
        }

        return $this->client->get($uri, $headers);
    }

    protected function anyArticle(bool $owned = true): Article
    {
        $q = $this->getService('doctrine.orm.entity_manager')->createQueryBuilder('a');
        $q->select('article');
        $q->from(Article::class, 'article');
        $q->innerJoin('article.author', 'author', Join::WITH, 'article.author = author');
        $q->where(
            $owned
                ? $q->expr()->eq('author.username', ':authorName')
                : $q->expr()->neq('author.username', ':authorName')
        );
        $q->setParameter('authorName', self::USERNAME_TEST);
        $q->setMaxResults(1);

        return current($q->getQuery()->getResult());
    }
}
