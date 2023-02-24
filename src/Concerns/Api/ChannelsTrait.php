<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ChannelsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets channel information for users.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-channel-information
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
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
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateChannels(array $parameters = [], array $body = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id']);

        return $this->patch('channels', $parameters, null, $body);
    }

    /**
     * Gets a list of users who have editor permissions for a specific channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-channel-editors
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getChannelEditors(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id']);

        return $this->patch('channels/editors', $parameters);
    }

    /**
     * Gets a list of users that follow the specified broadcaster.
     * You can also use this endpoint to see whether a specific user follows the broadcaster.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-channel-followers
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getChannelFollowers(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('channels/followers', $parameters, $paginator);
    }

    /**
     * Gets a list of broadcasters that the specified user follows.
     * You can also use this endpoint to see whether a user follows a specific broadcaster.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-followed-channels
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getChannelsFollowed(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['user_id']);

        return $this->get('channels/followed', $parameters, $paginator);
    }
}
