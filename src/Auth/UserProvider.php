<?php

namespace romanzipp\Twitch\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

abstract class UserProvider
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

    /**
     * Registers this user provider as new auth provider.
     *
     * Add this to your AuthServiceProvider::boot() method.
     */
    public static function register(string $name = 'laravel-twitch'): void
    {
        Auth::provider($name, fn ($app, array $config) => new static());
    }
}
