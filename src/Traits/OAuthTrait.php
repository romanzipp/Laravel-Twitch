<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Result;

trait OAuthTrait
{
    /**
     * Get OAuth authorize url.
     *
     * @see  https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/
     *
     * @param string $responseType code / token
     * @param array $scopes
     * @param string $state
     * @param bool $forceVerify
     * @return string
     */
    public function getOAuthAuthorizeUrl(string $responseType = 'code', array $scopes = [], string $state = null, bool $forceVerify = false): string
    {
        $query = [
            'response_type' => $responseType,
            'state' => $state,
            'client_id' => $this->getClientId(),
            'scope' => $this->buildScopes($scopes),
            'redirect_uri' => $this->getRedirectUri(),
        ];

        if ($state !== null) {
            $query['state'] = $state;
        }

        if ($forceVerify === true) {
            $query['force_verify'] = 'true';
        }

        return self::OAUTH_BASE_URI . 'authorize?' . http_build_query($query);
    }

    /**
     * Get an access token based on the OAuth code flow.
     *
     * @see  https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-authorization-code-flow
     *
     * @param string $code
     * @return Result
     */
    public function getOAuthToken(string $code): Result
    {
        $parameters = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri' => $this->getRedirectUri(),
        ];

        return $this->post(self::OAUTH_BASE_URI . 'token', $parameters);
    }

    /**
     * Build OAuth scopes to string.
     *
     * @param array $scopes
     * @return string
     */
    protected function buildScopes(array $scopes): string
    {
        return implode(' ', $scopes);
    }
}
