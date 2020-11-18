<?php

namespace romanzipp\Twitch\Tests\Api;

use Illuminate\Support\Facades\Cache;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

/**
 * This class tests the access token generation in real world.
 */
class AccessTokenTest extends ApiTestCase
{
    public function testAccessTokenGeneration(): void
    {
        $this->assertThatUserIsResolved('first test');
        $firstAccessToken = $this->getCachedKey()['access_token'];

        $this->invalidateAccessToken();
        $secondAccessToken = $this->getCachedKey()['access_token'];

        $this->assertThatUserIsResolved('second test');
        $thirdAccessToken = $this->getCachedKey()['access_token'];

        self::assertNotEquals($firstAccessToken, $secondAccessToken);
        self::assertNotEquals($secondAccessToken, $thirdAccessToken);
    }

    private function assertThatUserIsResolved(string $message): void
    {
        $this->registerResult(
            $result = $this->twitch()->getUsers(['id' => 12826])
        );

        self::assertTrue($result->success(), sprintf('Assert of %s failed: %s (%s)', $message, $result->error(), $result->status()));
        $this->assertHasProperties([
            'id', 'login', 'display_name', 'type', 'broadcaster_type', 'description',
            'profile_image_url', 'offline_image_url', 'view_count',
        ], $result->shift());
        self::assertEquals('twitch', $result->shift()->login);
    }

    /**
     * Here we generate an invalid access token for our test.
     *
     * Case: Twitch has invalidated our cached access token.
     */
    private function invalidateAccessToken(): void
    {
        $accessToken = $this->getCachedKey();
        self::assertIsArray($accessToken);
        $accessToken['access_token'] = 'nudfc6hcv6w5tj25vddqy98bz3gcc5';
        $this->twitch()->setToken($accessToken['access_token']);
        Cache::put(config('twitch-api.oauth_client_credentials.cache_key'), $accessToken);
    }

    private function getCachedKey(): array
    {
        $accessToken = Cache::get(config('twitch-api.oauth_client_credentials.cache_key'));
        self::assertNotNull($accessToken);
        return $accessToken;
    }
}
