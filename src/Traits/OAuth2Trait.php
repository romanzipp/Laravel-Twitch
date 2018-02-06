<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Result;

trait OAuth2Trait
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

        return $this->post('/kraken/oauth2/token', $attributes);
    }
}
