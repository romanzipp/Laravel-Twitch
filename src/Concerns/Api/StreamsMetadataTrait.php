<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractGetTrait;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait StreamsMetadataTrait
{
    use AbstractGetTrait;

    /**
     * Gets metadata information about active streams playing Overwatch or Hearthstone
     *
     * Parameters:
     * string   community_id    Returns streams in a specified community ID. You can specify up to 100 IDs.
     * string   game_id         Returns streams broadcasting the specified game ID. You can specify up to 100 IDs.
     * string   language        Stream language. You can specify up to 100 languages.
     * string   type            Stream type: "all", "live", "vodcast". Default: "all".
     * string   user_id         Returns streams broadcast by one or more of the specified user IDs. You can specify up to 100 IDs.
     * string   user_login      Returns streams broadcast by one or more of the specified user login names. You can specify up to 100 names.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-streams-metadata
     *
     * @param array $parameters Array of parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getStreamsMetadata(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('streams/metadata', $parameters, $paginator);
    }
}
