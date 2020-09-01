<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\PostTrait;
use romanzipp\Twitch\Result;

trait AdsTrait
{
    use PostTrait;

    /**
     * Starts a commercial on a specified channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#start-commercial
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result Result instance
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     */
    public function startCommercial(array $parameters = []): Result
    {
        if ( ! array_key_exists('broadcaster_id', $parameters)) {
            throw new InvalidArgumentException('Parameter required missing: broadcaster_id');
        }

        if ( ! array_key_exists('length', $parameters)) {
            throw new InvalidArgumentException('Parameter required missing: length');
        }

        return $this->post('channels/commercial', $parameters);
    }
}
