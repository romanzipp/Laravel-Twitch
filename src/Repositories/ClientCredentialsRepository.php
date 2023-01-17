<?php

namespace romanzipp\Twitch\Repositories;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;
use romanzipp\Twitch\Contracts\ClientCredentialsRepository as Repository;
use romanzipp\Twitch\Exceptions\OAuthTokenRequestException;
use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Twitch;

abstract class ClientCredentialsRepository implements Repository
{
    /**
     * Determine if an OAuth token should be fetched via the implemented flow.
     */
    public function shouldFetchClientCredentials(): bool
    {
        return config('twitch-api.oauth_client_credentials.auto_generate');
    }

    /**
     * Determine if the client credentials should be cached.
     */
    public function shouldCacheClientCredentials(): bool
    {
        return config('twitch-api.oauth_client_credentials.cache');
    }

    /**
     * Get the cache driver instance used for storing the client credentials.
     */
    protected function getClientCredentialsCacheRepository(): CacheRepository
    {
        return Cache::store(
            config('twitch-api.oauth_client_credentials.cache_store')
        );
    }

    protected function getCacheKey(): string
    {
        return config('twitch-api.oauth_client_credentials.cache_key');
    }

    /**
     * Fetch and possibly cache the OAuth token.
     *
     * @throws OAuthTokenRequestException
     * @throws InvalidArgumentException
     */
    abstract public function getClientCredentials(Twitch $twitch): ?AccessToken;

    /**
     * Possibly get a cached and not-expired version of the Access Token.
     *
     * @return AccessToken|null
     * @throws InvalidArgumentException
     */
    protected function getCachedClientCredentialsToken(): ?AccessToken
    {
        $stored = $this->getClientCredentialsCacheRepository()->get($this->getCacheKey());

        if (empty($stored)) {
            return null;
        }

        $token = new AccessToken($stored);

        if ( ! $token->isExpired()) {
            return $token;
        }

        $this->getClientCredentialsCacheRepository()->delete($this->getCacheKey());

        return null;
    }

    /**
     * Store the client credentials in cache.
     *
     * @throws InvalidArgumentException
     */
    public function storeClientCredentialsToken(AccessToken $token): void
    {
        $this->getClientCredentialsCacheRepository()->set($this->getCacheKey(), $token->toArray());
    }

    /**
     * Clear the client credentials from cache.
     */
    public function clearClientCredentialsToken(): void
    {
        $this->getClientCredentialsCacheRepository()->forget($this->getCacheKey());
    }
}

