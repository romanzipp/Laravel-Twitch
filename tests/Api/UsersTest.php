<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class UsersTest extends ApiTestCase
{
    public function testGetUserByName()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsers(['login' => 'twitch'])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertHasProperties([
            'id', 'login', 'display_name', 'type', 'broadcaster_type', 'description',
            'profile_image_url', 'offline_image_url', 'view_count',
        ], $result->shift());
        self::assertEquals(12826, (int) $result->shift()->id);
    }

    public function testGetUserById()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsers(['id' => 12826])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertHasProperties([
            'id', 'login', 'display_name', 'type', 'broadcaster_type', 'description',
            'profile_image_url', 'offline_image_url', 'view_count',
        ], $result->shift());
        self::assertEquals('twitch', $result->shift()->login);
    }

    public function testGetUsersByIds()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsers(['id' => [12826, 131784680]])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals(2, $result->count());
    }
}
