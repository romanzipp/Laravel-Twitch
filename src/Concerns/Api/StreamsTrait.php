<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractGetTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait StreamsTrait
{
    use AbstractValidationTrait;
    use AbstractGetTrait;

    /**
     * Gets the channel stream key for a user.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-stream-key
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getStreamKey(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('streams/key', $parameters);
    }

    /**
     * Gets information about active streams. Streams are returned sorted by number of current viewers, in descending order.
     * Across multiple pages of results, there may be duplicate or missing streams, as viewers join and leave streams.
     *
     * The response has a JSON payload with a data field containing an array of stream information elements and a pagination
     * field containing information required to query for more streams.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-streams
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getStreams(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('streams', $parameters, $paginator);
    }
}
