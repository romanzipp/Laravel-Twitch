<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\AbstractGetTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractPostTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractPutTrait;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait GamesTrait
{
    use AbstractGetTrait;
    use AbstractPostTrait;
    use AbstractPutTrait;

    /**
     * Gets games sorted by number of current viewers on Twitch, most popular first.
     * The response has a JSON payload with a data field containing an array of games information elements and a pagination field containing
     * information required to query for more streams.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-top-games
     *
     * @param array $parameters Array of parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
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
     * @param array $parameters Array of parameters
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getGames(array $parameters): Result
    {
        if ( ! array_key_exists('name', $parameters) && ! array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException('Required parameter missing: name or id');
        }

        return $this->get('games', $parameters);
    }

    /**
     * Gets game information by game ID
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param int $id Game ID
     * @return \romanzipp\Twitch\Result Result instance
     *
     * @todo remove
     */
    public function getGameById(int $id): Result
    {
        return $this->getGames([
            'id' => $id,
        ]);
    }

    /**
     * Gets game information by game name
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param string $name Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead,
     *                     query the specific Pokemon game(s) in which you are interested
     * @return \romanzipp\Twitch\Result Result instance
     *
     * @todo remove
     */
    public function getGameByName(string $name): Result
    {
        return $this->getGames([
            'name' => $name,
        ]);
    }

    /**
     * Gets games information by game IDs
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param array $ids Game IDs. At most 100 id values can be specified
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getGamesByIds(array $ids): Result
    {
        return $this->getGames([
            'id' => $ids,
        ]);
    }

    /**
     * Gets games information by game names
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param array $names Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead,
     *                     query the specific Pokemon game(s) in which you are interested. At most 100 name values can be specified
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getGamesByNames(array $names): Result
    {
        return $this->getGames([
            'name' => $names,
        ]);
    }
}
