<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait ChannelPoints
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Creates a Custom Reward on a channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-custom-rewards
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function createCustomRewards(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->post('channel_points/custom_rewards', $parameters);
    }
}
