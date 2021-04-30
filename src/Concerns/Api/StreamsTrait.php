<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait StreamsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets the channel stream key for a user.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-stream-key
     *
     * @param array $parameters
     *
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
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator Paginator instance
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getStreams(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('streams', $parameters, $paginator);
    }

    /**
     * Gets information about active streams belonging to channels that the authenticated user follows.
     * Streams are returned sorted by number of current viewers, in descending order. Across multiple pages of results, there may be
     * duplicate or missing streams, as viewers join and leave streams.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-followed-streams
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator Paginator instance
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getFollowedStreams(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['user_id']);

        return $this->get('streams/followed', $parameters, $paginator);
    }

    /**
     * Creates a marker in the stream of a user specified by a user ID. A marker is an arbitrary point in a stream that the
     * broadcaster wants to mark; e.g., to easily return to later. The marker is created at the current timestamp in the live
     * broadcast when the request is processed. Markers can be created by the stream owner or editors. The user creating the
     * marker is identified by a Bearer token.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#create-stream-marker
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function createStreamMarker(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, ['user_id']);

        return $this->post('streams/markers', $parameters, null, $body);
    }

    /**
     * Gets a list of markers for either a specified user’s most recent stream or a specified VOD/video (stream), ordered by
     * recency. A marker is an arbitrary point in a stream that the broadcaster wants to mark; e.g., to easily return to later.
     * The only markers returned are those created by the user identified by the Bearer token.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-stream-markers
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getStreamMarkers(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateAnyRequired($parameters, ['user_id', 'video_id']);

        return $this->get('streams/markers', $parameters, $paginator);
    }

    /**
     * Gets channel information for users.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-channel-information
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getChannels(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id']);

        return $this->get('channels', $parameters);
    }

    /**
     * Modifies channel information for users.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#modify-channel-information
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateChannels(array $parameters = [], array $body = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id']);

        return $this->patch('channels', $parameters, null, $body);
    }
}
