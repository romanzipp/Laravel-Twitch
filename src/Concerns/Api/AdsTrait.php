<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\PostTrait;
use romanzipp\Twitch\Result;

trait AdsTrait
{
    use PostTrait;

    /**
     * Starts a commercial on a specified channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#start-commercial
     *
     * @param string $broadcasterId ID of the channel requesting a commercial. Minimum: 1 Maximum: 1
     * @param int $length Desired length of the commercial in seconds. Valid options are 30, 60, 90, 120, 150, 180.
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function startCommercial(string $broadcasterId, int $length): Result
    {
        return $this->post('channels/commercial', [
            'broadcaster_id' => $broadcasterId,
            'length' => $length,
        ]);
    }
}
