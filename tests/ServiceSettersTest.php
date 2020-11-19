<?php

namespace romanzipp\Twitch\Tests;

use Illuminate\Support\Str;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceSettersTest extends TestCase
{
    public function testClientIdSetter()
    {
        $id = Str::random();

        $twitch = new Twitch();
        $twitch->setClientId($id);

        self::assertEquals($id, $twitch->getClientId());
    }

    public function testFluidClientIdSetter()
    {
        $id = Str::random();

        $twitch = new Twitch();
        $twitch->withClientId($id);

        self::assertEquals($id, $twitch->getClientId());
    }

    public function testFluidClientIdSetterFacade()
    {
        $id = Str::random();

        $twitch = TwitchFacade::withClientId($id);

        self::assertEquals($id, $twitch->getClientId());
    }

    public function testClientSecretSetter()
    {
        $secret = Str::random();

        $twitch = new Twitch();
        $twitch->setClientSecret($secret);

        self::assertEquals($secret, $twitch->getClientSecret());
    }

    public function testFluidClientSecretSetter()
    {
        $secret = Str::random();

        $twitch = new Twitch();
        $twitch->withClientSecret($secret);

        self::assertEquals($secret, $twitch->getClientSecret());
    }

    public function testFluidClientSecretSetterFacade()
    {
        $secret = Str::random();

        $twitch = TwitchFacade::withClientSecret($secret);

        self::assertEquals($secret, $twitch->getClientSecret());
    }

    public function testTokenSetter()
    {
        $token = Str::random();

        $twitch = new Twitch();
        $twitch->setToken($token);

        self::assertEquals($token, $twitch->getToken());
    }

    public function testFluidTokenSetter()
    {
        $token = Str::random();

        $twitch = new Twitch();
        $twitch->withToken($token);

        self::assertEquals($token, $twitch->getToken());
    }

    public function testFluidTokenSetterFacade()
    {
        $token = Str::random();

        $twitch = TwitchFacade::withToken($token);

        self::assertEquals($token, $twitch->getToken());
    }

    public function testRedirectUriSetter()
    {
        $uri = Str::random();

        $twitch = new Twitch();
        $twitch->setRedirectUri($uri);

        self::assertEquals($uri, $twitch->getRedirectUri());
    }

    public function testFluidRedirectUriSetter()
    {
        $uri = Str::random();

        $twitch = new Twitch();
        $twitch->withRedirectUri($uri);

        self::assertEquals($uri, $twitch->getRedirectUri());
    }

    public function testFluidRedirectUriSetterFacade()
    {
        $uri = Str::random();

        $twitch = TwitchFacade::withRedirectUri($uri);

        self::assertEquals($uri, $twitch->getRedirectUri());
    }
}
