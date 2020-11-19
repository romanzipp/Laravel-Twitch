<?php

namespace romanzipp\Twitch\Tests\Api;

use Illuminate\Support\Facades\Cache;
use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class AccessTokenTest extends ApiTestCase
{
    public function testAccessTokenIsCachedAfterRequest()
    {
        $store = Cache::store(
            config('twitch-api.oauth_client_credentials.cache_store')
        );

        self::assertNull(
            $store->get(config('twitch-api.oauth_client_credentials.cache_key'))
        );

        $this->registerResult(
            $result = $this->twitch()->getUsers(['login' => 'twitch'])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());

        self::assertNotNull(
            $store->get(config('twitch-api.oauth_client_credentials.cache_key'))
        );
    }

    public function testCacheIsClearedAfterReceivingInvalidOAuthError()
    {
        $store = Cache::store(
            config('twitch-api.oauth_client_credentials.cache_store')
        );

        $store->set(config('twitch-api.oauth_client_credentials.cache_key'), (new AccessToken([
            'access_token' => 'aaaa',
            'expires_in' => 10000,
            'token_type' => 'bearer',
        ]))->toArray());

        $this->registerResult(
            $result = $this->twitch()->getUsers(['login' => 'twitch'])
        );

        self::assertFalse($result->success());
        self::assertEquals(401, $result->getStatus());

        self::assertNull(
            $store->get(config('twitch-api.oauth_client_credentials.cache_key'))
        );
    }
}
