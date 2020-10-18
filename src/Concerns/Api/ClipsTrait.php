<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
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
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
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
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getClips(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id', 'game_id', 'id']);

        return $this->get('clips', $parameters);
    }
}
