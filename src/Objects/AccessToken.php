<?php

namespace romanzipp\Twitch\Objects;

use Illuminate\Contracts\Support\Arrayable;

class AccessToken implements Arrayable
{
    public $accessToken;

    public $refreshToken;

    public $expiresIn;

    public $scope;

    public $tokenType;

    public $expiresAt;

    public function __construct(array $data)
    {
        $this->accessToken = $data['access_token'];
        $this->refreshToken = $data['refresh_token'] ?? null;
        $this->expiresIn = $data['expires_in'];
        $this->scope = $data['scope'] ?? [];
        $this->tokenType = $data['token_type'];
        $this->expiresAt = $data['expires_at'] ?? time() + $this->expiresIn;
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
            'scope' => $this->scope,
            'token_type' => $this->tokenType,
            'expires_at' => $this->expiresAt,
        ];
    }

    public function isExpired(): bool
    {
        if (null === $this->expiresAt) {
            return true;
        }

        return time() > $this->expiresAt;
    }
}
