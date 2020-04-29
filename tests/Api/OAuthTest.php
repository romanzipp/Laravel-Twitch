<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Enums\GrantType;
use romanzipp\Twitch\Exceptions\RequestRequiresClientSecretException;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class OAuthTest extends ApiTestCase
{
    public function testGetOAuthTokenWithoutSecret()
    {
        $this->expectException(RequestRequiresClientSecretException::class);

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

        $this->assertEquals('bearer', $result->data->token_type);
        $this->assertIsString($result->data->access_token);
    }
}
