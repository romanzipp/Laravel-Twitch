<?php

namespace romanzipp\Twitch\Traits;

use BadMethodCallException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait EntitlementsTrait
{
    /**
     * Gets the status of one or more provided codes.
     * This API requires that the caller is an authenticated Twitch user.
     * The API is throttled to one request per second per authenticated user.
     * @param  array  $parameters   Array of parameters
     * @param  string $token        Twitch OAuth Token
     * @return Result               Result object
     * @see    https://dev.twitch.tv/docs/api/code-redemption-api/
     *
     * Parameters:
     * string   code    The code to get the status of. Repeat this query parameter additional times to get the status of multiple codes.
     * string   user_id Represents a numeric Twitch user ID. The user account which is going to receive the entitlement associated with the code.
     */
    public function getEntitlementsCodeStatus(array $parameters, string $token = null): Result
    {
        if (!array_key_exists('code', $parameters) && !array_key_exists('user_id', $parameters)) {
            throw new BadMethodCallException('Parameter required missing: code or user_id');
        }

        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->get('entitlements/codes', $parameters);
    }

    /**
     * Redeem one or more provided codes to the authenticated Twitch user.
     * This API requires that the caller is an authenticated Twitch user.
     * The API is throttled to one request per second per authenticated user.
     * @param  array  $parameters   Array of parameters
     * @param  string $token        Twitch OAuth Token
     * @return Result               Result object
     * @see    https://dev.twitch.tv/docs/api/code-redemption-api/
     *
     * Parameters:
     * string   code    The code to get the status of. Repeat this query parameter additional times to get the status of multiple codes.
     * string   user_id Represents a numeric Twitch user ID. The user account which is going to receive the entitlement associated with the code.
     */
    public function redeemEntitlementsCode(array $parameters, string $token = null): Result
    {
        if (!array_key_exists('code', $parameters) && !array_key_exists('user_id', $parameters)) {
            throw new BadMethodCallException('Parameter required missing: code or user_id');
        }

        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->json('POST', 'entitlements/codes', (array) []);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function json(string $method, string $path = '', array $body = null);

    abstract public function withToken(string $token);
}
