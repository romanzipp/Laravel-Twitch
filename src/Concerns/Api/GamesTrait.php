<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Concerns\Operations\PostTrait;
use romanzipp\Twitch\Concerns\Operations\PutTrait;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait GamesTrait
{
    use GetTrait;
    use PostTrait;
    use PutTrait;

    /**
     * Gets game information by given parameters
     *
     * Parameters:
     * string   id    Game ID. At most 100 id values can be specified.
     * string   name  Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead, query the specific Pokemon game(s) in which you are interested. At most 100 name values can be specified.
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param array $parameters Array of parameters
     * @return Result Result object
     */
    public function getGames(array $parameters): Result
    {
        if ( ! array_key_exists('name', $parameters) && ! array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException('Parameter required missing: name or id');
        }

        return $this->get('games', $parameters);
    }

    /**
     * Gets game information by game ID
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param int $id Game ID
     * @return Result Result object
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
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param string $name Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead,
     *                     query the specific Pokemon game(s) in which you are interested
     * @return Result Result object
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
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param array $ids Game IDs. At most 100 id values can be specified
     * @return Result Result object
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
     * @see    https://dev.twitch.tv/docs/api/reference#get-games
     *
     * @param array $names Game name. The name must be an exact match. For instance, "Pokemon" will not return a list of Pokemon games; instead,
     *                     query the specific Pokemon game(s) in which you are interested. At most 100 name values can be specified
     * @return Result Result object
     */
    public function getGamesByNames(array $names): Result
    {
        return $this->getGames([
            'name' => $names,
        ]);
    }

    /**
     * Gets games sorted by number of current viewers on Twitch, most popular first
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-top-games
     *
     * @param array $parameters Array of parameters
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
     */
    public function getTopGames(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('games/top', $parameters, $paginator);
    }
}
