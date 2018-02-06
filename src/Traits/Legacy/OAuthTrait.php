<?php

namespace romanzipp\Twitch\Traits\Legacy;

use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait OAuthTrait
{
    /**
     * [LEGACY v5] Refresh OAuth Token by given Refresh Token
     * @param  string      $refreshToken Refresh Token
     * @param  string      $clientSecret Client Secret
     * @param  string|null $scope        Scopes
     * @return Result
     * @see    https://dev.twitch.tv/docs/authentication#refreshing-access-tokens
     */
    public function legacyRefreshToken(string $refreshToken, string $clientSecret, string $scope = null): Result
    {
        if ($this->clientId === null) {
            throw new RequestRequiresAuthenticationException('Request requires Client ID');
        }

        $attributes = [
            'client_id' => $this->clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        if ($scope !== null) {
            $attributes['scope'] = $scope;
        }

        return $this->withLegacy()->post('/kraken/oauth2/token', $attributes);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function withLegacy();
}
