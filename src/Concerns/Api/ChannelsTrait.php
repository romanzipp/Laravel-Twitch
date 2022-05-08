<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
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
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateChannels(array $parameters = [], array $body = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id']);

        return $this->patch('channels', $parameters, null, $body);
    }
}
