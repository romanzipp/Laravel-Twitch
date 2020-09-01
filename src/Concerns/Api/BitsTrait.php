<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Result;

trait BitsTrait
{
    use GetTrait;

    /**
     * Get Bits leaderboard.
     * Parameters:
     * integer      count           Number of results to be returned. Maximum: 100. Default: 10.
     * string       period          Time period over which data is aggregated (PST time zone). This parameter interacts with started_at.
     * string       started_at      Timestamp for the period over which the returned data is aggregated. Must be in RFC 3339 format.
     * string       user_id         ID of the user whose results are returned; i.e., the person who paid for the bits.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-bits-leaderboard
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getBitsLeaderboard(array $parameters = []): Result
    {
        return $this->get('bits/leaderboard', $parameters);
    }
}
