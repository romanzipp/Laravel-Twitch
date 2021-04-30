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

    public function getPolls(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('polls', $parameters, $paginator);
    }

    public function createPolls(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'title',
            'choices',
            'duration',
        ]);

        return $this->post('polls', $parameters, null, $body);
    }

    public function updatePoll(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'id',
            'status',
        ]);

        return $this->patch('polls', $parameters, null, $body);
    }
}
