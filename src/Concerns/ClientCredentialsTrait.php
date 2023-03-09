<?php

namespace romanzipp\Twitch\Concerns;

use romanzipp\Twitch\Enums\GrantType;
use romanzipp\Twitch\Result;

trait ClientCredentialsTrait
{
    public function isAuthenticationUri(string $uri): bool
    {
        return str_starts_with($uri, self::OAUTH_BASE_URI);
    }

    abstract public function getOAuthToken(?string $code = null, string $grantType = GrantType::AUTHORIZATION_CODE, array $scopes = []): Result;

    abstract public function getClientId(): ?string;

    abstract public function getClientSecret(): ?string;
}
