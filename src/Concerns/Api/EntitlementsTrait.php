<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Concerns\Operations\PostTrait;
use romanzipp\Twitch\Result;

trait EntitlementsTrait
{
    use GetTrait;
    use PostTrait;

    /**
     * Create Entitlement Grants Upload URL
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-entitlement-grants-upload-url
     *
     * @param string $manifest Unique identifier of the manifest file to be uploaded. Must be 1-64 characters.
     * @param string $type Type of entitlement being granted. Only bulk_drops_grant is supported.
     * @return \romanzipp\Twitch\Result Result instance
     *
     * @todo Update to take single parameters array as argument
     */
    public function createEntitlementUrl(string $manifest, string $type = 'bulk_drops_grant'): Result
    {
        return $this->post('entitlements/upload', [
            'manifest_id' => $manifest,
            'type' => $type,
        ]);
    }

    /**
     * Gets the status of one or more provided codes. This API requires that the caller is an authenticated Twitch user.
     * The API is throttled to one request per second per authenticated user.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-code-status
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getEntitlementsCodeStatus(array $parameters = []): Result
    {
        if ( ! array_key_exists('code', $parameters)) {
            throw new InvalidArgumentException('Required parameter missing: code');
        }

        if ( ! array_key_exists('user_id', $parameters)) {
            throw new InvalidArgumentException('Required parameter missing: user_id');
        }

        return $this->get('entitlements/codes', $parameters);
    }

    /**
     * Gets a list of entitlements for a given organization that have been granted to a game, user, or both.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-drops-entitlements
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getDropsEntitlements(array $parameters = []): Result
    {
        return $this->get('entitlements/drops', $parameters);
    }

    /**
     * Redeems one or more provided codes to the authenticated Twitch user. This API requires that the caller is an authenticated Twitch user.
     * The API is throttled to one request per second per authenticated user. This API requires that the caller is an authenticated Twitch user.
     * The API is throttled to one request per second per authenticated user.
     *
     * @see https://dev.twitch.tv/docs/api/reference#redeem-code
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function redeemEntitlementsCode(array $parameters = []): Result
    {
        return $this->post('entitlements/code', $parameters);
    }
}
