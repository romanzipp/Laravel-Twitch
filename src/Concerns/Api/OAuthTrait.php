<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Enums\GrantType;
use romanzipp\Twitch\Result;

trait OAuthTrait
{
    use AbstractOperationsTrait;

    /**
     * Get OAuth authorize url.
     *
     * @see  https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/
     *
     * @param string $responseType code / token
     * @param array $scopes
     * @param string|null $state
     * @param bool $forceVerify
     *
     * @return string
     */
    public function getOAuthAuthorizeUrl(string $responseType = 'code', array $scopes = [], string $state = null, bool $forceVerify = false): string
    {
        $query = [
            'response_type' => $responseType,
            'client_id' => $this->getClientId(),
            'scope' => $this->buildScopes($scopes),
            'redirect_uri' => $this->getRedirectUri(),
        ];

        if (null !== $state) {
            $query['state'] = $state;
        }

        if (true === $forceVerify) {
            $query['force_verify'] = 'true';
        }

        return self::OAUTH_BASE_URI . 'authorize?' . http_build_query($query);
    }

    /**
     * Get an access token based on the OAuth code flow.
     *
     * @see  https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-authorization-code-flow
     *
     * @param string|null $code
     * @param string $grantType
     * @param array $scopes
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getOAuthToken(?string $code = null, string $grantType = GrantType::AUTHORIZATION_CODE, array $scopes = []): Result
    {
        if ( ! $clientId = $this->getClientId()) {
            throw new InvalidArgumentException('The OAuth request requires a client secret to be set');
        }

        if ( ! $clientSecret = $this->getClientSecret()) {
            throw new InvalidArgumentException('The OAuth request requires a client secret to be set');
        }

        $parameters = [
            'grant_type' => $grantType,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];

        if (GrantType::AUTHORIZATION_CODE === $grantType) {
            $parameters['redirect_uri'] = $this->getRedirectUri();
        }

        if (null !== $code) {
            $parameters['code'] = $code;
        }

        if ( ! empty($scopes)) {
            $parameters['scope'] = $this->buildScopes($scopes);
        }

        return $this->post(self::OAUTH_BASE_URI . 'token', $parameters);
    }

    /**
     * Build OAuth scopes to string.
     *
     * @param array $scopes
     *
     * @return string
     */
    protected function buildScopes(array $scopes): string
    {
        return implode(' ', $scopes);
    }
}
