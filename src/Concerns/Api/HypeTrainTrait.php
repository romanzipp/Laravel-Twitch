<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait HypeTrainTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets the information of the most recent Hype Train of the given channel ID. When there is currently an active Hype Train,
     * it returns information about that Hype Train. When there is currently no active Hype Train, it returns information about
     * the most recent Hype Train.  After 5 days, if no Hype Train has been active, the endpoint will return an empty response.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-hype-train-events
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getHypeTrainEvents(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('hypetrain/events', $parameters);
    }
}
