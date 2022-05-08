<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait ChatTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets all emotes that the specified Twitch channel created. Broadcasters create these custom emotes for users who subscribe to or follow the channel,
     * or cheer Bits in the channel’s chat window. For information about the custom emotes, see subscriber emotes, Bits tier emotes, and follower emotes.
     *
     * NOTE: With the exception of custom follower emotes, users may use custom emotes in any Twitch chat.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-channel-emotes
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getChannelChatEmotes(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('chat/emotes', $parameters);
    }

    /**
     * Gets all global emotes. Global emotes are Twitch-created emoticons that users can use in any Twitch chat.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-global-emotes
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getGlobalChatEmotes(): Result
    {
        return $this->get('chat/emotes/global');
    }

    /**
     * Gets emotes for one or more specified emote sets.
     *
     * An emote set groups emotes that have a similar context. For example,
     * Twitch places all the subscriber emotes that a broadcaster uploads for their channel in the same emote set.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-emote-sets
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getChatEmoteSets(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['emote_set_id']);

        return $this->get('chat/emotes/set', $parameters);
    }

    /**
     * Gets a list of custom chat badges that can be used in chat for the specified channel. This includes subscriber badges and Bit badges.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-channel-chat-badges
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getChannelChatBadges(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('chat/badges', $parameters);
    }

    /**
     * Gets a list of chat badges that can be used in chat for any channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-global-chat-badges
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getGlobalChatBadges(): Result
    {
        return $this->get('chat/badges/global');
    }

    /**
     * Gets the broadcaster’s chat settings.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-chat-settings
     *
     * @param array<string, mixed> $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getChatSettings(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('chat/settings', $parameters);
    }

    /**
     * https://dev.twitch.tv/docs/api/reference#update-chat-settings.
     *
     * @see Updates the broadcaster’s chat settings.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateChatSettings(array $parameters, array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->patch('chat/settings', $parameters, null, $body);
    }
}
