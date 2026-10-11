<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ClipsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Creates a clip programmatically. This returns both an ID and an edit URL for the new clip.
     * Note that the clips service returns a maximum of 1000 clips,.
     *
     * Clip creation takes time. We recommend that you query Get Clips, with the clip ID that is returned here. If Get Clips returns a valid clip,
     * your clip creation was successful. If, after 15 seconds, you still have not gotten back a valid clip from Get Clips, assume that the clip
     * was not created and retry Create Clip.
     *
     * This endpoint has a global rate limit, across all callers. The limit may change over time, but the response includes informative headers:
     * Ratelimit-Helixclipscreation-Limit: <int value>
     * Ratelimit-Helixclipscreation-Remaining: <int value>
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-clip
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result Result instance
     */
    public function createClip(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->post('clips', $parameters);
    }

    /**
     * Gets clip information by clip ID (one or more), broadcaster ID (one only), or game ID (one only).
     *
     * Note that the clips service returns a maximum of 1000 clips,
     *
     * The response has a JSON payload with a data field containing an array of clip information elements and a pagination
     * field containing information required to query for more streams.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-clips
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result Result instance
     */
    public function getClips(array $parameters = [], ?Paginator $paginator = null): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id', 'game_id', 'id']);

        return $this->get('clips', $parameters, $paginator);
    }

    /**
     * Creates a clip from a broadcaster’s VOD on behalf of the broadcaster or an editor of the channel. Since a live stream is actively creating a VOD, this endpoint can also be used to create a clip from earlier in the current stream.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-clip-from-vod
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function createClipFromVod(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['editor_id', 'broadcaster_id', 'vod_id', 'vod_offset', 'title']);

        return $this->post('videos/clips', $parameters);
    }

    /**
     * Provides URLs to download the video file(s) for the specified clips. For information about clips, see How to use clips. These links are temporary and should have a long-term expectation to expire.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-clips-download
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getClipsDownload(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['editor_id', 'broadcaster_id', 'clip_id']);

        return $this->get('clips/downloads', $parameters);
    }
}
