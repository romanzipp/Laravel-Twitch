<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class BitsTest extends ApiTestCase
{
    public function testUnauthenticated()
    {
        $this->registerResult(
            $result = $this->twitch()->getBitsLeaderboard()
        );

        self::assertFalse($result->success());
        self::assertEquals(401, $result->getStatus());
    }
}
