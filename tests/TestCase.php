<?php

namespace romanzipp\Twitch\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\Twitch\Facades\Twitch;
use romanzipp\Twitch\Providers\TwitchServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TwitchServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Twitch' => Twitch::class,
        ];
    }
}
