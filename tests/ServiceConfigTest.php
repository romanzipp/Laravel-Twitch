<?php

namespace romanzipp\Twitch\Tests;

use Illuminate\Support\Str;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceConfigTest extends TestCase
{
    public function testClientIdConfig()
    {
        $id = Str::random();

        config(['twitch-api.client_id' => $id]);

        $twitch = new Twitch();

        self::assertEquals($id, $twitch->getClientId());
    }

    public function testClientIdConfigFacade()
    {
        $id = Str::random();

        config(['twitch-api.client_id' => $id]);

        self::assertEquals($id, TwitchFacade::getClientId());

        config(['twitch-api.client_id' => Str::random()]);

        self::assertEquals($id, TwitchFacade::getClientId());
    }

    public function testClientSecretConfig()
    {
        $secret = Str::random();

        config(['twitch-api.client_secret' => $secret]);

        $twitch = new Twitch();

        self::assertEquals($secret, $twitch->getClientSecret());
    }

    public function testClientSecretConfigFacade()
    {
        $secret = Str::random();

        config(['twitch-api.client_secret' => $secret]);

        self::assertEquals($secret, TwitchFacade::getClientSecret());

        config(['twitch-api.client_secret' => Str::random()]);

        self::assertEquals($secret, TwitchFacade::getClientSecret());
    }
}
