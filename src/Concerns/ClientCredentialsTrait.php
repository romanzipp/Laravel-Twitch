<?php

namespace romanzipp\Twitch\Concerns;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use romanzipp\Twitch\Enums\GrantType;
use romanzipp\Twitch\Exceptions\OAuthTokenRequestException;
use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Result;

trait ClientCredentialsTrait
{
    /**
     * Determine if an OAuth token should be fetched via the Client Credentials flow.
     *
     * @see https://dev.twitch.tv/docs/authentication/getting-tokens-oauth#oauth-client-credentials-flow
     *
     * @return bool
     */
    protected function shouldFetchClientCredentials(): bool
    {
        return config('twitch-api.oauth_client_credentials.auto_generate')
            && null !== $this->getClientId()
            && null !== $this->getClientSecret();
    }

    /**
     * Determine if the client credentials should be cached.
     *
     * @return bool
     */
    protected function shouldCacheClientCredentials(): bool
    {
        return config('twitch-api.oauth_client_credentials.cache');
    }

    /**
     * Fetch and possibly cache the OAuth token.
     *
     * @see https://dev.twitch.tv/docs/authentication/getting-tokens-oauth#oauth-client-credentials-flow
     *
     * @return \romanzipp\Twitch\Objects\AccessToken|null
     */
    protected function getClientCredentials(): ?AccessToken
    {
        if ($this->shouldCacheClientCredentials() && $token = $this->getCachedClientCredentialsToken()) {
            return $token;
        }

        $result = $this->getOAuthToken(null, GrantType::CLIENT_CREDENTIALS);

        if ( ! $result->success()) {
            $exception = $result->getException();

            if (null === $exception) {
                return null;
            }

            throw new OAuthTokenRequestException('Could not fetch the OAuth credentials', $exception->getRequest(), $result->getResponse(), $exception);
        }

        $token = new AccessToken(
            (array) $result->data()
        );

        if ($this->shouldCacheClientCredentials()) {
            $this->storeClientCredentialsToken($token);
        }

        return $token;
    }

    /**
     * Possibly get a cached and not-expired version of the Access Token.
     *
     * @return \romanzipp\Twitch\Objects\AccessToken|null
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function getCachedClientCredentialsToken(): ?AccessToken
    {
        $key = config('twitch-api.oauth_client_credentials.cache_key');

        $stored = $this->getClientCredentialsCacheRepository()->get($key);

        if (empty($stored)) {
            return null;
        }

        $token = new AccessToken($stored);

        if ( ! $token->isExpired()) {
            return $token;
        }

        $this->getClientCredentialsCacheRepository()->delete($key);

        return null;
    }

    /**
     * Store the client credentials in cache.
     *
     * @param \romanzipp\Twitch\Objects\AccessToken $token
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function storeClientCredentialsToken(AccessToken $token): void
    {
        $this->getClientCredentialsCacheRepository()->set(
            config('twitch-api.oauth_client_credentials.cache_key'),
            $token->toArray()
        );
    }

    /**
     * Clear the client credentials from cache.
     */
    protected function clearClientCredentialsToken(): void
    {
        $this->getClientCredentialsCacheRepository()->forget(
            config('twitch-api.oauth_client_credentials.cache_key')
        );
    }

    /**
     * Get the cache driver instance used for storing the client credentials.
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function getClientCredentialsCacheRepository(): Repository
    {
        return Cache::store(
            config('twitch-api.oauth_client_credentials.cache_store')
        );
    }

    public function isAuthenticationUri(string $uri): bool
    {
        return 0 === strpos($uri, self::OAUTH_BASE_URI);
    }

    abstract public function getOAuthToken(?string $code = null, string $grantType = GrantType::AUTHORIZATION_CODE, array $scopes = []): Result;

    abstract public function getClientId(): ?string;

    abstract public function getClientSecret(): ?string;
}
