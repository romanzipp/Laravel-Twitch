<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class ApiBitsTest extends ApiTestCase
{
    public function testUnauthenticated()
    {
        $this->registerResult(
            $result = $this->twitch()->getBitsLeaderboard()
        );

        $this->assertFalse($result->success);
    }
}
