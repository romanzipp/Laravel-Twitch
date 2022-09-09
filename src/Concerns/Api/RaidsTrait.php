<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait RaidsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Raid another channel by sending the broadcaster’s viewers to the targeted channel.
     *
     * When you call the API from a chat bot or extension, the Twitch UX pops up a window at the top of the chat room
     * that identifies the number of viewers in the raid. The raid occurs when the broadcaster clicks Raid Now or
     * after the 90 second countdown expires.
     *
     * To cancel a pending raid, call Cancel a raid.
     *
     * To determine whether the raid successfully occurred, you must subscribe to the Channel Raid event.
     * For more information, see Get notified when a raid begins.
     *
     * Rate Limit: The limit is 10 requests within a 10-minute window.
     *
     * @see https://dev.twitch.tv/docs/api/reference#start-a-raid
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function startRaid(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['from_broadcaster_id', 'to_broadcaster_id']);

        return $this->post('raids', $parameters);
    }

    /**
     * Cancel a pending raid.
     *
     * You can cancel a raid at any point up until the broadcaster clicks Raid Now in the Twitch UX or the 90 seconds countdown expires.
     *
     * Rate Limit: The limit is 10 requests within a 10-minute window.
     *
     * @see https://dev.twitch.tv/docs/api/reference#cancel-a-raid
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function cancelRaid(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->delete('raids');
    }
}
