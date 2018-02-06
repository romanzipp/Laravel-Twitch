<?php

namespace romanzipp\Twitch\Traits\Legacy;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait OAuthTrait
{
    public function refreshToken(string $refreshToken, string $clientSecret, string $scope = null): Result
    {
        if ($this->clientId == null) {
            throw new Exception('Request requires Client ID');
        }

        $attributes = [
            'client_id' => $this->clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        if ($scope != null) {
            $attributes['scope'] = $scope;
        }

        return $this->withLegacy()->post('/kraken/oauth2/token', $attributes);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
