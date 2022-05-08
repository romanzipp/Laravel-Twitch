<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ModerationTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Determines whether a string message meets the channel’s AutoMod requirements.
     *
     * AutoMod is a moderation tool that blocks inappropriate or harassing chat with powerful moderator control.
     * AutoMod detects misspellings and evasive language automatically. AutoMod uses machine learning and natural language
     * processing algorithms to hold risky messages from chat so they can be reviewed by a channel moderator before appearing
     * to other viewers in the chat. Moderators can approve or deny any message caught by AutoMod.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#check-automod-status
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function checkAutoModStatus(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->post('moderation/enforcements/status', $parameters, null, $body);
    }

    /**
     * Returns all banned and timed-out users in a channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-banned-users
     *
     * @param array<string, mixed> $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getBannedUsers(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('moderation/banned', $parameters, $paginator);
    }

    /**
     * Returns all user bans and un-bans in a channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-banned-events
     *
     * @param array<string, mixed> $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     *
     * @deprecated since 2022‑03‑18 "Removed documentation for “Get Banned Events” and “Get Moderator Events” Twitch API endpoints."
     * @see https://discuss.dev.twitch.tv/t/deprecation-of-twitch-api-event-endpoints-that-supported-websub-based-webhooks/35137
     */
    public function getBannedEvents(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('moderation/banned/events', $parameters, $paginator);
    }

    /**
     * Returns all moderators in a channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-moderators
     *
     * @param array<string, mixed> $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getModerators(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('moderation/moderators', $parameters, $paginator);
    }

    /**
     * Returns a list of moderators or users added and removed as moderators from a channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-moderator-events
     *
     * @param array<string, mixed> $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     *
     * @deprecated since 2022‑03‑18 "Removed documentation for “Get Banned Events” and “Get Moderator Events” Twitch API endpoints."
     * @see https://discuss.dev.twitch.tv/t/deprecation-of-twitch-api-event-endpoints-that-supported-websub-based-webhooks/35137
     */
    public function getModeratorEvents(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('moderation/moderators/events', $parameters, $paginator);
    }
}
