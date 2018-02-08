<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait StreamsMetadataTrait
{
    /**
     * Gets metadata information about active streams playing Overwatch or Hearthstone
     * @param  array          $parameters Array of parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-streams-metadata
     *
     * Parameters:
     * string   community_id    Returns streams in a specified community ID. You can specify up to 100 IDs.
     * string   game_id         Returns streams broadcasting the specified game ID. You can specify up to 100 IDs.
     * string   language        Stream language. You can specify up to 100 languages.
     * string   type            Stream type: "all", "live", "vodcast". Default: "all".
     * string   user_id         Returns streams broadcast by one or more of the specified user IDs. You can specify up to 100 IDs.
     * string   user_login      Returns streams broadcast by one or more of the specified user login names. You can specify up to 100 names.
     */
    public function getStreamsMetadata(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('streams/metadata', $parameters, $paginator);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
