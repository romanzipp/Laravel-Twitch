<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait PollsTrait
{
    use AbstractOperationsTrait;
    use AbstractValidationTrait;

    /**
     * Get information about all polls or specific polls for a Twitch channel. Poll information is available for 90 days.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-polls
     *
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getPolls(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('polls', $parameters, $paginator);
    }

    /**
     * Create a poll for a specific Twitch channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-poll
     *
     * @param array $parameters
     * @param array $body
     *
     * @return Result
     */
    public function createPoll(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'title',
            'choices',
            'duration',
        ]);

        return $this->post('polls', $parameters, null, $body);
    }

    /**
     * End a poll that is currently active.
     *
     * @see https://dev.twitch.tv/docs/api/reference#end-poll
     *
     * @param array $parameters
     * @param array $body
     *
     * @return Result
     */
    public function endPoll(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'id',
            'status',
        ]);

        return $this->patch('polls', $parameters, null, $body);
    }
}
