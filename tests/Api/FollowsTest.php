<?php

namespace romanzipp\Twitch\Tests\Api;

use BadMethodCallException;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class FollowsTest extends ApiTestCase
{
    public function testMissingParameters()
    {
        $this->expectException(BadMethodCallException::class);

        $this->twitch()->getFollows();
    }

    public function testGetFollowsWithFrom()
    {
        $this->registerResult(
            $result = $this->twitch()->getFollowsFrom(12826)
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());
        $this->assertEquals(12826, (int) $result->shift()->from_id);
    }

    public function testGetFollowsWithTo()
    {
        $this->registerResult(
            $result = $this->twitch()->getFollowsTo(12826)
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());
        $this->assertEquals(12826, (int) $result->shift()->to_id);
    }
}
