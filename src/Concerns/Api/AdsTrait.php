<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait AdsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Starts a commercial on a specified channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#start-commercial
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function startCommercial(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id', 'length']);

        return $this->post('channels/commercial', $parameters);
    }
}
