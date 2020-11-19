<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait GamesTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets games sorted by number of current viewers on Twitch, most popular first.
     * The response has a JSON payload with a data field containing an array of games information elements and a pagination field containing
     * information required to query for more streams.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-top-games
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator Paginator instance
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getTopGames(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('games/top', $parameters, $paginator);
    }

    /**
     * Gets game information by game ID or name.
     * The response has a JSON payload with a data field containing an array of games elements.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getGames(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['id', 'name']);

        return $this->get('games', $parameters);
    }
}
