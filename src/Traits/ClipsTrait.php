<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait ClipsTrait
{
    /**
     * Creates a clip programmatically. This returns both an ID and an edit URL for the new clip.
     *
     * @see    https://dev.twitch.tv/docs/api/reference#create-clip
     *
     * @param  int    $broadcaster ID of the stream from which the clip will be made.
     * @return Result Result object
     */
    public function createClip(int $broadcaster): Result
    {
        return $this->post('clips', [
            'broadcaster_id' => $broadcaster,
        ]);
    }

    /**
     * Gets information about a specified clip.
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-clip
     *
     * @param  string $id    ID of the clip being queried. Limit 1.
     * @return Result Result object
     */
    public function getClip(string $id): Result
    {
        return $this->get('clips', [
            'id' => $id,
        ]);
    }

    /**
     * Create Entitlement Grants Upload URL
     *
     * @see    https://dev.twitch.tv/docs/api/reference#create-entitlement-grants-upload-url
     *
     * @param  string $manifest Unique identifier of the manifest file to be uploaded. Must be 1-64 characters.
     * @param  string $type     Type of entitlement being granted. Only bulk_drops_grant is supported.
     * @return Result Result object
     */
    public function createEntitlementUrl(string $manifest, string $type = 'bulk_drops_grant'): Result
    {
        return $this->post('entitlements/upload', [
            'manifest_id' => $manifest,
            'type'        => $type,
        ]);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
