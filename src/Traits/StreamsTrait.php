<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait StreamsTrait
{
    /**
     * Gets information about active streams
     * @param  array          $parameters Array of parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     *
     * Parameters:
     * string   community_id    Returns streams in a specified community ID. You can specify up to 100 IDs.
     * string   game_id         Returns streams broadcasting a specified game ID. You can specify up to 100 IDs.
     * string   language        Stream language. You can specify up to 100 languages.
     * string   type            Stream type: "all", "live", "vodcast". Default: "all".
     * string   user_id         Returns streams broadcast by one or more specified user IDs. You can specify up to 100 IDs.
     * string   user_login      Returns streams broadcast by one or more specified user login names. You can specify up to 100 names.
     */
    public function getStreams(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('streams', $parameters, $paginator);
    }

    /**
     * Gets information about active streams by user ID
     * @param  int            $id         Returns streams broadcast by one specified user ID
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByUserId(int $id, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['user_id'] = $id;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by user name
     * @param  int            $name       Returns streams broadcast by one specified user login name
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByUserName(string $name, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['user_login'] = $name;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by user IDs
     * @param  int            $ids        Returns streams broadcast by one or more specified user IDs. You can specify up to 100 IDs
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByUserIds(array $ids, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['user_id'] = $ids;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by user IDs
     * @param  int            $ids        Returns streams broadcast by one or more specified user login names. You can specify up to 100 names
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByUserNames(array $names, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['user_login'] = $names;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by community ID
     * @param  int            $id         Returns streams in a specified community ID
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByCommunity(int $id, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['community_id'] = $id;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by community IDs
     * @param  int            $ids        Returns streams in a specified community IDs. You can specify up to 100 IDs
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByCommunities(int $ids, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['community_id'] = $ids;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by game ID
     * @param  int            $id         Returns streams in a specified game ID
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByGame(int $id, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['game_id'] = $id;

        return $this->getStreams($parameters, $paginator);
    }

    /**
     * Gets information about active streams by game IDs
     * @param  int            $ids        Returns streams in a specified game IDs.  You can specify up to 100 IDs
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams
     */
    public function getStreamsByGames(int $ids, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['game_id'] = $ids;

        return $this->getStreams($parameters, $paginator);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
