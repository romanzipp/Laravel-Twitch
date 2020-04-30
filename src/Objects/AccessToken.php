<?php

namespace romanzipp\Twitch\Objects;

use Illuminate\Contracts\Support\Arrayable;
use stdClass;

class AccessToken implements Arrayable
{
    public $accessToken;

    public $refreshToken;

    public $expiresIn;

    public $scope;

    public $tokenType;

    public function __construct(stdClass $data)
    {
        $this->accessToken = $data->access_token;
        $this->refreshToken = $data->refresh_token ?? null;
        $this->expiresIn = $data->expires_in;
        $this->scope = $data->scope ?? [];
        $this->tokenType = $data->token_type;
    }

    public function toArray()
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
            'scope' => $this->scope,
            'token_type' => $this->tokenType,
        ];
    }

    public function isExpired(): bool
    {
        return true;
    }
}
