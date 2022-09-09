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
     * @param array<string, mixed> $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getVideos(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateAnyRequired($parameters, ['id', 'user_id', 'game_id']);

        return $this->get('videos', $parameters, $paginator);
    }

    /**
     * Deletes one or more videos. Videos are past broadcasts, Highlights, or uploads.
     *
     * Invalid Video IDs will be ignored (i.e. IDs provided that do not have a video associated with it).
     * If the OAuth user token does not have permission to delete even one of the valid Video IDs,
     * no videos will be deleted and the response will return a 401.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-videos
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function deleteVideos(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['id']);

        return $this->delete('videos');
    }
}
