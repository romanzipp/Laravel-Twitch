<?php

namespace romanzipp\Twitch\Tests\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class FollowsTest extends ApiTestCase
{
    public function testMissingParameters()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->twitch()->getUsersFollows();
    }

    public function testGetFollowsWithFrom()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsersFollows(['from_id' => 12826])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties(['from_id', 'from_name', 'to_id', 'to_name', 'followed_at'], $result->shift());
        self::assertEquals(12826, (int) $result->shift()->from_id);
    }

    public function testGetFollowsWithTo()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsersFollows(['to_id' => 12826])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties(['from_id', 'from_name', 'to_id', 'to_name', 'followed_at'], $result->shift());
        self::assertEquals(12826, (int) $result->shift()->to_id);
    }
}
