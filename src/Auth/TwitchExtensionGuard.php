<?php

namespace romanzipp\Twitch\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TwitchExtensionGuard
{
    public static string $CACHE_KEY = 'twitch:auth.%s';

    public static bool $WITH_CACHE = false;

    protected TwitchUserProvider $twitchUserProvider;

    /**
     * The secrets of the twitch extension guard.
     *
     * @var array<Key>
     */
    private static array $extSecretKeys = [];

    /**
     * Create a new authentication guard.
     *
     * @param TwitchUserProvider $twitchUserProvider
     */
    public function __construct(TwitchUserProvider $twitchUserProvider)
    {
        $this->twitchUserProvider = $twitchUserProvider;
    }

    /**
     * Adds a secret for the twitch extension guard.
     *
     * @param string $secret
     */
    public static function addExtSecret(string $secret): void
    {
        self::$extSecretKeys[] = new Key(base64_decode($secret), 'HS256');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function user(Request $request)
    {
        if ( ! ($token = $request->headers->get('Authorization'))) {
            return null;
        }

        if ( ! Str::startsWith($token, 'Twitch')) {
            return null;
        }

        try {
            $token = explode(' ', $token)[1] ?? null;

            $fn = function () use ($token) {
                $decoded = $this->decodeAuthorizationToken($token);

                return $this->resolveUser($decoded);
            };

            if ( ! self::$WITH_CACHE) {
                return $fn();
            }

            return cache()->remember($this->getCacheKey($token), now()->addMinutes(5), $fn);
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function getCacheKey(string $token): string
    {
        return sprintf(self::$CACHE_KEY, sha1($token));
    }

    private function decodeAuthorizationToken(string $token): \stdClass
    {
        foreach (self::$extSecretKeys as $extSecretKey) {
            try {
                return JWT::decode($token, $extSecretKey);
            } catch (SignatureInvalidException $exception) {
                // do nothing
            }
        }

        throw new SignatureInvalidException('Twitch extension signature verification failed.');
    }

    /**
     * Registers the twitch extension guard as new auth guard.
     *
     * Add this to your AuthServiceProvider::boot() method.
     */
    public static function register(string $secret, string $driver = 'laravel-twitch'): void
    {
        self::addExtSecret($secret);
        Auth::extend($driver, function ($app, $name, array $config) {
            return new RequestGuard(function ($request) {
                return (new self(app(TwitchUserProvider::class)))->user($request);
            }, app('request'));
        });
    }

    /**
     * @param mixed $decoded
     *
     * @return mixed|null
     */
    private function resolveUser($decoded)
    {
        $user = $this->twitchUserProvider->retrieveById($decoded->user_id);
        $user = $user ?? $this->twitchUserProvider->createFromTwitchToken($decoded);

        if (null === $user) {
            return null;
        }

        if (method_exists($user, 'withTwitchToken')) {
            $user = $user->withTwitchToken($decoded);
        }

        return $user;
    }
}
