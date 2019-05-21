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
     * @param  string   $responseType  code / token
     * @param  array    $scopes
     * @param  string   $state
     * @param  bool     $forceVerify
     * @return string
     */
    public function getOAuthAuthorizeUrl(string $responseType = 'code', array $scopes = [], string $state = null, bool $forceVerify = false): string
    {
        $query = [
            'client_id'     => $this->getClientId(),
            'response_type' => $responseType,
            'scope'         => $this->buildScopes($scopes),
            'redirect_uri'  => $this->getRedirectUri(),
            'state'         => $state,
        ];

        if ($state !== null) {
            $query['state'] = $state;
        }

        if ($forceVerify === true) {
            $query['force_verify'] = 1;
        }

        return self::OAUTH_BASE_URI . 'authorize?' . http_build_query($query);
    }

    public function getOAuthToken(): Result
    {
        return $this->post(self::OAUTH_BASE_URI . 'token');
    }

    /**
     * Build OAuth scopes to string.
     *
     * @param  array    $scopes
     * @return string
     */
    protected function buildScopes(array $scopes): string
    {
        return implode('+', $scopes);
    }
}
