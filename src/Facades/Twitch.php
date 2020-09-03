<?php

namespace romanzipp\Twitch\Facades;

use Illuminate\Support\Facades\Facade;
use romanzipp\Twitch\Twitch as TwitchService;

/**
 * @method static \romanzipp\Twitch\Twitch withClientId(string $clientId)
 * @method static \romanzipp\Twitch\Twitch withClientSecret(string $clientSecret)
 * @method static \romanzipp\Twitch\Twitch withRedirectUri(string $redirectUri)
 */
class Twitch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwitchService::class;
    }
}
