<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class UsersTest extends ApiTestCase
{
    public function testGetUserByName()
    {
        $this->registerResult(
            $result = $this->twitch()->getUserByName('twitch')
        );

        $this->assertTrue($result->success());
        $this->assertHasProperties([
            'id', 'login', 'display_name', 'type', 'broadcaster_type', 'description',
            'profile_image_url', 'offline_image_url', 'view_count',
        ], $result->shift());
        $this->assertEquals(12826, (int) $result->shift()->id);
    }

    public function testGetUserById()
    {
        $this->registerResult(
            $result = $this->twitch()->getUserById(12826)
        );

        $this->assertTrue($result->success());
        $this->assertHasProperties([
            'id', 'login', 'display_name', 'type', 'broadcaster_type', 'description',
            'profile_image_url', 'offline_image_url', 'view_count',
        ], $result->shift());
        $this->assertEquals('twitch', $result->shift()->login);
    }

    public function testGetUsersByIds()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsersByIds([12826, 131784680])
        );

        $this->assertTrue($result->success());
        $this->assertEquals(2, $result->count());
    }
}
