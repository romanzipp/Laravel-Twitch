<?php

namespace romanzipp\Twitch\Objects;

use Illuminate\Contracts\Support\Arrayable;

class AccessToken implements Arrayable
{
    /**
     * @var string
     */
    public $accessToken;

    /**
     * @var string
     */
    public $refreshToken;

    /**
     * @var int
     */
    public $expiresIn;

    /**
     * @var string
     */
    public $scope;

    /**
     * @var string
     */
    public $tokenType;

    /**
     * @var int
     */
    public $expiresAt;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->accessToken = $data['access_token'];
        $this->refreshToken = $data['refresh_token'] ?? null;
        $this->expiresIn = $data['expires_in'];
        $this->scope = $data['scope'] ?? [];
        $this->tokenType = $data['token_type'];
        $this->expiresAt = $data['expires_at'] ?? time() + $this->expiresIn;
    }

    /**
     * @return array<string, mixed>
     */
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

    public function fromJsonOrString(string $jsonOrString): self
    {
        if (!str_contains($jsonOrString, '{')) {
            return new self([
                'access_token' => $jsonOrString,
                'expires_in' => 86400,
                'token_type' => 'client_credentials',
            ]);
        }

        return new self(json_decode($jsonOrString, true));
    }
}
