<?php

namespace romanzipp\Twitch\Repositories;

use Aws\Exception\AwsException;
use Aws\SecretsManager\SecretsManagerClient;
use romanzipp\Twitch\Exceptions\OAuthTokenRequestException;
use romanzipp\Twitch\Objects\AccessToken;
use romanzipp\Twitch\Twitch;

class AwsClientCredentialsRepository extends ClientCredentialsRepository
{
    private SecretsManagerClient $client;

    public function __construct(SecretsManagerClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws OAuthTokenRequestException
     */
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
        try {
            $result = $this->client->getSecretValue([
                'SecretId' => config('twitch-api.oauth_client_credentials.secret_manager.aws.secret_id'),
            ]);
        } catch (AwsException $exception) {
            throw new OAuthTokenRequestException('Could not fetch the OAuth access token from AWS.', $exception->getCode(), $exception);
        }

        if (isset($result['SecretString'])) {
            return $result['SecretString'];
        } else {
            return base64_decode($result['SecretBinary']);
        }
    }
}
