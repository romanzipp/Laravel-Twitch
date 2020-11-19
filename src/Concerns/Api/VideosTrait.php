<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait VideosTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets video information by video ID (one or more), user ID (one only), or game ID (one only).
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-videos
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getVideos(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateAnyRequired($parameters, ['id', 'user_id', 'game_id']);

        return $this->get('videos', $parameters, $paginator);
    }
}
