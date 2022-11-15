<?php

declare(strict_types=1);

namespace App\Tests\unit\Security;

use App\Tests\unit\BaseTest;

use function Safe\json_decode;
use function var_dump;

class AuthTest extends BaseTest
{
    public function testLoginto(): void
    {
        $response = $this->sendRequest(
            '/api/login_check',
            [
                'username' => self::USERNAME_TEST,
                'password' => self::PASSWORD_TEST
            ]
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('token', json_decode($response->getBody()->getContents(), true));
    }
}
