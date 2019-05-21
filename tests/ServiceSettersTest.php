<?php

namespace romanzipp\Twitch\Tests;

use Illuminate\Support\Str;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceSettersTest extends TestCase
{
    public function testClientIdSetter()
    {
        $id = Str::random();

        $twitch = new Twitch;
        $twitch->setClientId($id);

        $this->assertEquals($id, $twitch->getClientId());
    }

    public function testFluidClientIdSetter()
    {
        $id = Str::random();

        $twitch = new Twitch;
        $twitch->withClientId($id);

        $this->assertEquals($id, $twitch->getClientId());
    }

    public function testFluidClientIdSetterFacade()
    {
        $id = Str::random();

        $twitch = TwitchFacade::withClientId($id);

        $this->assertEquals($id, $twitch->getClientId());
    }

    public function testClientSecretSetter()
    {
        $secret = Str::random();

        $twitch = new Twitch;
        $twitch->setClientSecret($secret);

        $this->assertEquals($secret, $twitch->getClientSecret());
    }

    public function testFluidClientSecretSetter()
    {
        $secret = Str::random();

        $twitch = new Twitch;
        $twitch->withClientSecret($secret);

        $this->assertEquals($secret, $twitch->getClientSecret());
    }

    public function testFluidClientSecretSetterFacade()
    {
        $secret = Str::random();

        $twitch = TwitchFacade::withClientSecret($secret);

        $this->assertEquals($secret, $twitch->getClientSecret());
    }

    public function testTokenSetter()
    {
        $token = Str::random();

        $twitch = new Twitch;
        $twitch->setToken($token);

        $this->assertEquals($token, $twitch->getToken());
    }

    public function testFluidTokenSetter()
    {
        $token = Str::random();

        $twitch = new Twitch;
        $twitch->withToken($token);

        $this->assertEquals($token, $twitch->getToken());
    }

    public function testFluidTokenSetterFacade()
    {
        $token = Str::random();

        $twitch = TwitchFacade::withToken($token);

        $this->assertEquals($token, $twitch->getToken());
    }
}
