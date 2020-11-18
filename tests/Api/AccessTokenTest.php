<?php

namespace romanzipp\Twitch\Tests\Api;

use Illuminate\Support\Facades\Cache;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

/**
 * This class tests the access token generation in real world.
 */
class AccessTokenTest extends ApiTestCase
{
    public function testAccessTokenRegeneration(): void
    {
        Cache::store()->put('twitch-api-client-credentials', [
            'access_token' => 't9qdvfu6jvfe6suhu6a98cr4ekjmkp',
            'refresh_token' => null,
            'expires_in' => 5635903,
            'scope' => [],
            'token_type' => 'bearer',
            'expires_at' => time() + 5635903,
        ]);

        $this->registerResult(
            $result = $this->twitch()->getUsers(['id' => 12826])
        );

        self::assertTrue($result->success(), sprintf('Got %s (%s).', $result->error(), $result->status()));
        $this->assertHasProperties([
            'id', 'login', 'display_name', 'type', 'broadcaster_type', 'description',
            'profile_image_url', 'offline_image_url', 'view_count',
        ], $result->shift());
        self::assertEquals('twitch', $result->shift()->login);
    }
}
