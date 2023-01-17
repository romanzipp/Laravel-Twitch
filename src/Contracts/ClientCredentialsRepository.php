<?php

namespace romanzipp\Twitch\Contracts;

use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Twitch;

interface ClientCredentialsRepository
{
    /**
     * Determine if an OAuth token should be fetched via the implemented flow.
     */
    public function shouldFetchClientCredentials(): bool;

    /**
     * Determine if the client credentials should be cached.
     */
    public function shouldCacheClientCredentials(): bool;

    /**
     * Fetch and possibly cache the OAuth token.
     */
    public function getClientCredentials(Twitch $twitch): ?AccessToken;

    /**
     * Store the client credentials in cache.
     */
    public function storeClientCredentialsToken(AccessToken $token): void;

    /**
     * Clear the client credentials from cache.
     */
    public function clearClientCredentialsToken(): void;
}
