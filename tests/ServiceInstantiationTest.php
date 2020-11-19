<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceInstantiationTest extends TestCase
{
    public function testInstance()
    {
        self::assertInstanceOf(Twitch::class, app(Twitch::class));
    }

    public function testFacade()
    {
        self::assertInstanceOf(Twitch::class, TwitchFacade::getFacadeRoot());
    }
}
