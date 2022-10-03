<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait TeamsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Retrieves a list of Twitch Teams of which the specified channel/broadcaster is a member.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-channel-teams
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getChannelTeams(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('teams/channel', $parameters);
    }

    /**
     * Gets information for a specific Twitch Team.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-teams
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getTeams(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['id', 'name']);

        return $this->get('teams', $parameters);
    }
}
