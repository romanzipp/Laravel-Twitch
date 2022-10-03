<?php

namespace romanzipp\Twitch\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

abstract class TwitchUserProvider
{
    /**
     * Retrieve a user by their unique twitch identifier.
     *
     * @param mixed $identifier
     *
     * @return Authenticatable|null
     */
    abstract public function retrieveById($identifier): ?Authenticatable;

    /**
     * Create a user by they decoded twitch extension jwt token.
     *
     * @param mixed $decoded
     *
     * @return Authenticatable|null
     */
    public function createFromTwitchToken($decoded): ?Authenticatable
    {
        return null;
    }
}
