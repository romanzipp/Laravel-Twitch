<?php

namespace romanzipp\Twitch\Traits;

use BadMethodCallException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait VideosTrait
{
    /**
     * Gets video information by video ID (one or more), user ID (one only), or game ID (one only)
     * @param  array  $parameters Array of parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-videos
     *
     * Parameters:
     * string   id          ID of the video being queried. Limit: 100. If this is specified, you cannot use any of the optional query string parameters below.
     * string   user_id     ID of the user who owns the video. Limit 1.
     * string   game_id     ID of the game the video is of. Limit 1.
     * string   language    Language of the video being queried. Limit: 1.
     * string   period      Period during which the video was created. Valid values: "all", "day", "month", and "week". Default: "all".
     * string   sort        Sort order of the videos. Valid values: "time", "trending", and "views". Default: "time".
     * string   type        Type of video. Valid values: "all", "upload", "archive", and "highlight". Default: "all".
     */
    public function getVideos(array $parameters, Paginator $paginator = null): Result
    {
        if (!array_key_exists('id', $parameters) && !array_key_exists('user_id', $parameters) && !array_key_exists('game_id', $parameters)) {
            throw new BadMethodCallException('Parameter required missing: id, user_id or game_id');
        }

        return $this->get('videos', $parameters, $paginator);
    }

    /**
     * Gets video information by video ID
     * @param  int            $id         ID of the video being queried
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-videos
     */
    public function getVideosById(int $id, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['id'] = $id;

        return $this->getVideos($parameters, $paginator);
    }

    /**
     * Gets video information by user ID
     * @param  int            $user       ID of the user who owns the video. Limit 1
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-videos
     */
    public function getVideosByUser(int $user, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['user_id'] = $user;

        return $this->getVideos($parameters, $paginator);
    }

    /**
     * Gets video information by game ID
     * @param  int            $game       ID of the game the video is of. Limit 1
     * @param  array          $parameters Additional parameters
     * @param  Paginator|null $paginator  Paginator object
     * @return Result                     Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-videos
     */
    public function getVideosByGame(int $game, array $parameters = [], Paginator $paginator = null): Result
    {
        $parameters['game_id'] = $game;

        return $this->getVideos($parameters, $paginator);
    }
}
