<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Facades\Twitch;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class ApiUsersTest extends ApiTestCase
{
    public function testGetUserByName()
    {
        $this->registerResult($result = Twitch::getUserByName('twitch'));

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals('12826', $result->shift()->id);
    }

    public function testGetUserById()
    {
        $this->registerResult($result = Twitch::getUserById('12826'));

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals('twitch', $result->shift()->login);
    }
}
