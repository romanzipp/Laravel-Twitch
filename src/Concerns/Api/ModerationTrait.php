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

    /**
     * Allow or deny a message that was held for review by AutoMod.
     * In order to retrieve messages held for review, use the chat_moderator_actions topic via PubSub. For more information about AutoMod, see How to Use AutoMod.
     *
     * @see https://dev.twitch.tv/docs/api/reference#manage-held-automod-messages
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function manageHeldAutoModMessages(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, ['user_id', 'msg_id', 'action']);

        return $this->post('moderation/automod/message', $parameters, null, $body);
    }

    /**
     * Gets the broadcaster’s AutoMod settings, which are used to automatically block inappropriate or harassing messages from appearing in the broadcaster’s chat room.
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getAutoModSettings(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->get('moderation/automod/settings', $parameters);
    }

    /**
     * Updates the broadcaster’s AutoMod settings, which are used to automatically block inappropriate or harassing messages from appearing in the broadcaster’s chat room.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-automod-settings
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateAutoModSettings(array $parameters = [], array $body = []): Result
    {
        return $this->put('moderation/automod/settings', $parameters, null, $body);
    }

    /**
     * Bans a user from participating in a broadcaster’s chat room, or puts them in a timeout. For more information about banning or putting users in a timeout, see Ban a User and Timeout a User.
     * If the user is currently in a timeout, you can call this endpoint to change the duration of the timeout or ban them altogether. If the user is currently banned, you cannot call this method to put them in a timeout instead.
     * To remove a ban or end a timeout, see Unban user.
     *
     * @see https://dev.twitch.tv/docs/api/reference#ban-user
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function banUser(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->post('moderation/bans', $parameters, null, $body);
    }

    /**
     * Removes the ban or timeout that was placed on the specified user (see Ban user).
     *
     * @see https://dev.twitch.tv/docs/api/reference#ban-user
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function unbanUser(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'user_id']);

        return $this->delete('moderation/bans', $parameters);
    }

    /**
     * Gets the broadcaster’s list of non-private, blocked words or phrases. These are the terms that the broadcaster or moderator added manually, or that were denied by AutoMod.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-blocked-terms
     *
     * @param array<string, mixed> $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getBlockedTerms(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->get('moderation/blocked_terms', $parameters, $paginator);
    }

    /**
     * Adds a word or phrase to the broadcaster’s list of blocked terms. These are the terms that broadcasters don’t want used in their chat room.
     *
     * @see https://dev.twitch.tv/docs/api/reference#add-blocked-term
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function addBlockedTerm(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->post('moderation/blocked_terms', $parameters, null, $body);
    }

    /**
     * Removes the word or phrase that the broadcaster is blocking users from using in their chat room.
     *
     * @see https://dev.twitch.tv/docs/api/reference#remove-blocked-term
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function removeBlockedTerm(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'id', 'moderator_id']);

        return $this->delete('moderation/blocked_terms', $parameters);
    }

    /**
     * Removes a single chat message or all chat messages from the broadcaster’s chat room.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-chat-messages
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function deleteChatMessages(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->delete('moderation/chat', $parameters);
    }

    /**
     * Adds a moderator to the broadcaster’s chat room.
     *
     * Rate Limits: The channel may add a maximum of 10 moderators within a 10 seconds period.
     *
     * @see https://dev.twitch.tv/docs/api/reference#add-channel-moderator
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function addChannelModerator(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'user_id']);

        return $this->get('moderation/moderators', $parameters);
    }

    /**
     * Removes a moderator from the broadcaster’s chat room.
     *
     * Rate Limits: The channel may remove a maximum of 10 moderators within a 10 seconds period.
     *
     * @see https://dev.twitch.tv/docs/api/reference#remove-channel-moderator
     *
     * @param array $parameters
     *
     * @return Result
     */
    public function removeChannelModerator(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'user_id']);

        return $this->delete('moderation/moderators', $parameters);
    }

    /**
     * Gets a list of the channel’s VIPs.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-vips
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getVIPs(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('channels/vips', $parameters, $paginator);
    }

    /**
     * Adds a VIP to the broadcaster’s chat room.
     *
     * Rate Limits: The channel may add a maximum of 10 VIPs within a 10 seconds period.
     *
     * @see https://dev.twitch.tv/docs/api/reference#add-channel-vip
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function addChannelVIP(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['user_id', 'broadcaster_id']);

        return $this->post('channels/vips', $parameters);
    }

    /**
     * Removes a VIP from the broadcaster’s chat room.
     *
     * Rate Limits: The channel may add a maximum of 10 VIPs within a 10 seconds period.
     *
     * @see https://dev.twitch.tv/docs/api/reference#remove-channel-vip
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function removeChannelVIP(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['user_id', 'broadcaster_id']);

        return $this->delete('channels/vips', $parameters);
    }
}
