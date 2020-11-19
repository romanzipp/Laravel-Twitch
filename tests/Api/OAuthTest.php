<?php

namespace romanzipp\Twitch\Tests\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Enums\GrantType;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class OAuthTest extends ApiTestCase
{
    public function testGetOAuthTokenWithoutSecret()
    {
        $this->expectException(InvalidArgumentException::class);

        $twitch = $this->twitch();
        $twitch->setClientSecret('');

        $twitch->getOAuthToken(null, GrantType::CLIENT_CREDENTIALS);
    }

    public function testGetOAuthTokenWithClientCredentialsFlow()
    {
        $this->skipIfClientSecretMissing();

        $this->registerResult(
            $result = $this->twitch()->getOAuthToken(null, GrantType::CLIENT_CREDENTIALS)
        );

        self::assertEquals('bearer', $result->data()->token_type);
        self::assertIsString($result->data()->access_token);
    }
}
