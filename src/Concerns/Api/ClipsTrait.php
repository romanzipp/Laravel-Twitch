<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Concerns\Operations\PostTrait;
use romanzipp\Twitch\Result;

trait ClipsTrait
{
    use GetTrait;
    use PostTrait;

    /**
     * Creates a clip programmatically. This returns both an ID and an edit URL for the new clip.
     * Note that the clips service returns a maximum of 1000 clips,
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
     * @param int $broadcaster ID of the stream from which the clip will be made.
     * @return \romanzipp\Twitch\Result Result instance
     *
     * @todo Update to take single parameters array as argument
     */
    public function createClip(int $broadcaster): Result
    {
        return $this->post('clips', [
            'broadcaster_id' => $broadcaster,
        ]);
    }

    /**
     * Gets information about a specified clip.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-clip
     *
     * @param string $id ID of the clip being queried. Limit 1.
     * @return \romanzipp\Twitch\Result Result instance
     *
     * @todo Update to take single parameters array as argument
     *
     */
    public function getClip(string $id): Result
    {
        return $this->get('clips', [
            'id' => $id,
        ]);
    }
}
