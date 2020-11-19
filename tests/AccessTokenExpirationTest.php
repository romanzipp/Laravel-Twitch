<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class AccessTokenExpirationTest extends TestCase
{
    public function testExpiration()
    {
        self::assertFalse($this->token(time() + 30)->isExpired());
        self::assertFalse($this->token(time() + time())->isExpired());
        self::assertFalse($this->token(time() + time() + 30)->isExpired());
        self::assertTrue($this->token(time() - 30)->isExpired());
        self::assertTrue($this->token(time() - time())->isExpired());
        self::assertTrue($this->token(time() - time() - 30)->isExpired());
    }

    private function token(int $expiresAt): AccessToken
    {
        $token = new AccessToken([
            'access_token' => 'foo',
            'expires_in' => time() - $expiresAt,
            'token_type' => 'client_credentials',
        ]);

        $token->expiresAt = $expiresAt;

        return $token;
    }
}
