<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceInstantiationTest extends TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(Twitch::class, app(Twitch::class));
    }

    public function testFacade()
    {
        $this->assertInstanceOf(Twitch::class, TwitchFacade::getFacadeRoot());
    }
}
