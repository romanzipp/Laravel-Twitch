<?php

namespace romanzipp\Twitch\Repositories;

use Google\ApiCore\ApiException;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use romanzipp\Twitch\Exceptions\OAuthTokenRequestException;
use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Twitch;

class GoogleClientCredentialsRepository extends ClientCredentialsRepository
{
    private SecretManagerServiceClient $client;

    public function __construct(SecretManagerServiceClient $client)
    {
        $this->client = $client;
    }

    public function getClientCredentials(Twitch $twitch): ?AccessToken
    {
        if ($this->shouldCacheClientCredentials() && $token = $this->getCachedClientCredentialsToken()) {
            return $token;
        }

        $token = AccessToken::fromJsonOrString($this->getSecretPayload());

        if ($this->shouldCacheClientCredentials()) {
            $this->storeClientCredentialsToken($token);
        }

        return $token;
    }

    /**
     * @throws OAuthTokenRequestException
     */
    public function getSecretPayload(): string
    {
        $name = $this->client->secretVersionName(
            config('twitch-api.oauth_client_credentials.secret_manager.google.project_id'),
            config('twitch-api.oauth_client_credentials.secret_manager.google.secret_id'),
            config('twitch-api.oauth_client_credentials.secret_manager.google.secret_version')
        );

        try {
            $result = $this->client->accessSecretVersion($name);
        } catch (ApiException $exception) {
            throw new OAuthTokenRequestException('Could not fetch the OAuth access token from Google.', $exception->getCode(), $exception);
        }

        return $result->getPayload()->getData();
    }
}
