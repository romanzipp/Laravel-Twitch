<?php

namespace romanzipp\Twitch\Facades;

use Illuminate\Support\Facades\Facade;
use romanzipp\Twitch\Twitch as TwitchService;

class Twitch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwitchService::class;
    }
}
