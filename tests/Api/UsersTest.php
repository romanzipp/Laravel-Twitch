<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class UsersTest extends ApiTestCase
{
    public function testGetUserByName()
    {
        $this->registerResult(
            $result = $this->twitch()->getUserByName('twitch')
        );

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals('12826', $result->shift()->id);
    }

    public function testGetUserById()
    {
        $this->registerResult(
            $result = $this->twitch()->getUserById('12826')
        );

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals('twitch', $result->shift()->login);
    }

    public function testGetUsersByIds()
    {
        $this->registerResult(
            $result = $this->twitch()->getUsersByIds([12826, 131784680])
        );

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals(2, $result->count());
    }
}
