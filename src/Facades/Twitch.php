<?php

namespace romanzipp\Twitch\Facades;

use Illuminate\Support\Facades\Facade;

class Twitch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \romanzipp\Twitch\Twitch::class;
    }
}
