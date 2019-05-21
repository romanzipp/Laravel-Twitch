<?php

namespace romanzipp\Twitch\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\Twitch\Providers\TwitchServiceProvider;
use romanzipp\Twitch\Twitch;

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
