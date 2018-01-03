<?php

namespace romanzipp\Twitch\Facades;

use Illuminate\Support\Facades\Facade;

class TwitchServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'romanzipp\Twitch\Twitch';
    }
}
