<?php

namespace romanzipp\Twitch\Repositories;

use Psr\SimpleCache\InvalidArgumentException;
use romanzipp\Twitch\Enums\GrantType;
use romanzipp\Twitch\Exceptions\OAuthTokenRequestException;
use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Twitch;

class TwitchClientCredentialsRepository extends ClientCredentialsRepository
{
    /**
     * @inheritdoc
     *
     * @see https://dev.twitch.tv/docs/authentication/getting-tokens-oauth#oauth-client-credentials-flow
     */
    public function getClientCredentials(Twitch $twitch): ?AccessToken
    {
        if ($this->shouldCacheClientCredentials() && $token = $this->getCachedClientCredentialsToken()) {
            return $token;
        }

        $result = $twitch->getOAuthToken(null, GrantType::CLIENT_CREDENTIALS);

        if ( ! $result->success()) {
            $exception = $result->getException();

            if (null === $exception) {
                return null;
            }

            throw new OAuthTokenRequestException('Could not fetch the OAuth access token from Twitch.', $exception->getCode(), $exception);
        }

        $token = new AccessToken((array) $result->data());

        if ($this->shouldCacheClientCredentials()) {
            $this->storeClientCredentialsToken($token);
        }

        return $token;
    }

}
