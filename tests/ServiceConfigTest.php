<?php

namespace romanzipp\Twitch\Tests;

use Illuminate\Support\Str;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceConfigTest extends TestCase
{
    public function testClientIdConfig()
    {
        $id = Str::random();

        config(['twitch-api.client_id' => $id]);

        $twitch = new Twitch;

        $this->assertEquals($id, $twitch->getClientId());
    }

    public function testClientIdConfigFacade()
    {
        $id = Str::random();

        config(['twitch-api.client_id' => $id]);

        $this->assertEquals($id, TwitchFacade::getClientId());

        config(['twitch-api.client_id' => Str::random()]);

        $this->assertEquals($id, TwitchFacade::getClientId());
    }

    public function testClientSecretConfig()
    {
        $secret = Str::random();

        config(['twitch-api.client_secret' => $secret]);

        $twitch = new Twitch;

        $this->assertEquals($secret, $twitch->getClientSecret());
    }

    public function testClientSecretConfigFacade()
    {
        $secret = Str::random();

        config(['twitch-api.client_secret' => $secret]);

        $this->assertEquals($secret, TwitchFacade::getClientSecret());

        config(['twitch-api.client_secret' => Str::random()]);

        $this->assertEquals($secret, TwitchFacade::getClientSecret());
    }
}
