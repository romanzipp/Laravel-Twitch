<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait ExtentionsTrait
{
    /**
     * Get currently authed user's extenions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference#get-user-extensions"
     */
    public function getAuthedUserExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }
        return $this->get('users/extensions/list', [], null);
    }

    /**
     * Get currently authed user's active extenions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference#get-user-active-extensions"
     */
    public function getAuthedUserActiveExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->get('users/extensions', [], null);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function withToken();

}
