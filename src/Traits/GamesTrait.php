<?php

namespace romanzipp\Twitch\Traits;

use BadMethodCallException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait GamesTrait
{
    /**
     * Gets game information by given parameters
     * @param  array  $parameters Array of parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     *
     * Parameters:
     * string   id    Game ID. At most 100 id values can be specified.
     * string   name  Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead, query the specific Pokemon game(s) in which you are interested. At most 100 name values can be specified.
     */
    public function getGames(array $parameters): Result
    {
        if (!array_key_exists('name', $parameters) && !array_key_exists('id', $parameters)) {
            throw new BadMethodCallException('Parameter required missing: name or id');
        }

        return $this->get('games', $parameters);
    }

    /**
     * Gets game information by game ID
     * @param  int    $id Game ID
     * @return Result     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     */
    public function getGameById(int $id): Result
    {
        return $this->getGames([
            'id' => $id,
        ]);
    }

    /**
     * Gets game information by game name
     * @param  string $name Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead, query the specific Pokemon game(s) in which you are interested
     * @return Result       Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     */
    public function getGameByName(string $name): Result
    {
        return $this->getGames([
            'name' => $name,
        ]);
    }

    /**
     * Gets games information by game IDs
     * @param  array  $ids Game IDs. At most 100 id values can be specified
     * @return Result      Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     */
    public function getGamesByIds(array $ids): Result
    {
        return $this->getGames([
            'id' => $ids,
        ]);
    }

    /**
     * Gets games information by game names
     * @param  array  $names Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead, query the specific Pokemon game(s) in which you are interested. At most 100 name values can be specified
     * @return Result        Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     */
    public function getGamesByNames(array $names): Result
    {
        return $this->getGames([
            'name' => $names,
        ]);
    }

    /**
     * Gets games sorted by number of current viewers on Twitch, most popular first
     * @param  Paginator|null $paginator Paginator object
     * @return Result                    Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-top-games
     */
    public function getTopGames(Paginator $paginator = null): Result
    {
        return $this->get('games/top', [], $paginator);
    }

    abstract public function get($path = '', $parameters = [], $token = null, Paginator $paginator = null);

    abstract public function post($path = '', $parameters = [], $token = null, Paginator $paginator = null);

    abstract public function put($path = '', $parameters = [], $token = null, Paginator $paginator = null);
}
