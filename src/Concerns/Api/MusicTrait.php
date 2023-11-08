<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait MusicTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets the Soundtrack track that the broadcaster is playing.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-soundtrack-current-track
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getSoundtrackCurrentTrack(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('soundtrack/current_track', $parameters);
    }

    /**
     * Gets the tracks of a Soundtrack playlist.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-soundtrack-playlist
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getSoundtrackPlaylist(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['id']);

        return $this->get('soundtrack/playlist', $parameters);
    }

    /**
     * Gets a list of Soundtrack playlists.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-soundtrack-playlists
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getSoundtrackPlaylists(array $parameters = []): Result
    {
        return $this->get('soundtrack/playlists', $parameters);
    }
}
