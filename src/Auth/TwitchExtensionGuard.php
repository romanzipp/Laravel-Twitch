<?php

namespace romanzipp\Twitch\Auth;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;

class TwitchExtensionGuard
{
    public static string $CACHE_KEY = 'twitch:auth.%s';

    public static bool $WITH_CACHE = false;

    protected UserProvider $userProvider;

    /**
     * The secrets of the twitch extension guard.
     *
     * @var array
     */
    private static array $extSecrets = [];

    /**
     * Create a new authentication guard.
     *
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Adds a secret for the twitch extension guard.
     *
     * @param string $secret
     */
    public static function addExtSecret(string $secret)
    {
        static::$extSecrets[] = $secret;
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
        } catch (Exception $exception) {
            return null;
        }
    }

    private function getCacheKey($token): string
    {
        return sprintf(self::$CACHE_KEY, sha1($token));
    }

    private function decodeAuthorizationToken(string $token): stdClass
    {
        foreach (self::$extSecrets as $extSecret) {
            try {
                return JWT::decode($token, base64_decode($extSecret), ['HS256']);
            } catch (SignatureInvalidException $exception) {
                // do nothing
            }
        }

        throw new SignatureInvalidException('Twitch extension sSignature verification failed.');
    }

    /**
     * Registers the twitch extension guard as new auth guard.
     *
     * Add this to your AuthServiceProvider::boot() method.
     *
     * @param string $secret
     * @param UserProvider $twitchUserProvider
     * @param string $driver
     */
    public static function register(string $secret, UserProvider $twitchUserProvider, string $driver = 'twitch'): void
    {
        self::addExtSecret($secret);
        Auth::extend($driver, function ($app, $name, array $config) use ($twitchUserProvider) {
            return new RequestGuard(function ($request) use ($twitchUserProvider) {
                return (new self($twitchUserProvider))->user($request);
            }, app('request'));
        });
    }

    /**
     * @param $decoded
     *
     * @return mixed|null
     */
    private function resolveUser($decoded)
    {
        $user = $this->userProvider->retrieveById($decoded->user_id);
        $user = $user ?? $this->userProvider->createFromTwitchToken($decoded);

        if (null === $user) {
            return null;
        }

        if (method_exists($user, 'withTwitchToken')) {
            $user = $user->withTwitchToken($decoded);
        }

        $user->cached_at = now();

        return $user;
    }
}
