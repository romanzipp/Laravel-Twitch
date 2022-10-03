<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait GoalsTrait
{
    use AbstractOperationsTrait;
    use AbstractValidationTrait;

    /**
     * Gets the broadcaster’s list of active goals. Use this to get the current progress of each goal.
     * Alternatively, you can subscribe to receive notifications when a goal makes progress using the channel.goal.progress subscription type. Read more.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-creator-goals
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getCreatorGoals(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('goals', $parameters);
    }
}
