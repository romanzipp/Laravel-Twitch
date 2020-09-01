<?php

namespace romanzipp\Twitch\Concerns\Api;

use BadMethodCallException;
use romanzipp\Twitch\Concerns\Operations\AbstractGetTrait;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait VideosTrait
{
    use AbstractGetTrait;

    /**
     * Gets video information by video ID (one or more), user ID (one only), or game ID (one only)
     *
     * Parameters:
     * string   id          ID of the video being queried. Limit: 100. If this is specified, you cannot use any of the optional query string parameters below.
     * string   user_id     ID of the user who owns the video. Limit 1.
     * string   game_id     ID of the game the video is of. Limit 1.
     * string   language    Language of the video being queried. Limit: 1.
     * string   period      Period during which the video was created. Valid values: "all", "day", "month", and "week". Default: "all".
     * string   sort        Sort order of the videos. Valid values: "time", "trending", and "views". Default: "time".
     * string   type        Type of video. Valid values: "all", "upload", "archive", and "highlight". Default: "all".
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-videos
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getVideos(array $parameters, Paginator $paginator = null): Result
    {
        if ( ! array_key_exists('id', $parameters) && ! array_key_exists('user_id', $parameters) && ! array_key_exists('game_id', $parameters)) {
            throw new BadMethodCallException('Required parameter missing: id, user_id or game_id');
        }

        return $this->get('videos', $parameters, $paginator);
    }

    /**
     * Gets video information by video ID
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-videos
     *
     * @param int $id ID of the video being queried
     * @param array $parameters Additional parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getVideosById(int $id, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['id'] = $id;

        return $this->getVideos($parameters, $paginator);
    }

    /**
     * Gets video information by user ID
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-videos
     *
     * @param int $user ID of the user who owns the video. Limit 1
     * @param array $parameters Additional parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getVideosByUser(int $user, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['user_id'] = $user;

        return $this->getVideos($parameters, $paginator);
    }

    /**
     * Gets video information by game ID
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-videos
     *
     * @param int $game ID of the game the video is of. Limit 1
     * @param array $parameters Additional parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getVideosByGame(int $game, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['game_id'] = $game;

        return $this->getVideos($parameters, $paginator);
    }
}
