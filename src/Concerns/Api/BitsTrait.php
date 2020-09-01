<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Result;

trait BitsTrait
{
    use GetTrait;

    /**
     * Retrieves the list of available Cheermotes, animated emotes to which viewers can assign Bits, to cheer in chat.
     * Cheermotes returned are available throughout Twitch, in all Bits-enabled channels.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-cheermotes
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getCheermotes(array $parameters = []): Result
    {
        if ( ! array_key_exists('broadcaster_id', $parameters)) {
            throw new InvalidArgumentException('Required parameter missing: broadcaster_id');
        }

        return $this->get('bits/cheermotes', $parameters);
    }

    /**
     * Get Bits leaderboard.
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

    /**
     * Get Extension Transactions allows extension back end servers to fetch a list of transactions that have occurred for their extension across all of Twitch.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extension-transactions
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getExtensionTransactions(array $parameters = []): Result
    {
        if ( ! array_key_exists('extension_id', $parameters)) {
            throw new InvalidArgumentException('Required parameter missing: extension_id');
        }

        return $this->get('extensions/transactions', $parameters);
    }
}
