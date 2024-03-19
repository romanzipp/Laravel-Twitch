<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
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
     * @return Result Result instance
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
     * @return Result Result instance
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
     * @return Result Result instance
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
     * @return Result Result instance
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
     * @return Result Result instance
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
     * @return Result
     */
    public function getChatSettings(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('chat/settings', $parameters);
    }

    /**
     * Updates the broadcaster’s chat settings.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-chat-settings
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateChatSettings(array $parameters, array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->patch('chat/settings', $parameters, null, $body);
    }

    /**
     * Sends an announcement to the broadcaster’s chat room.
     *
     * @see https://dev.twitch.tv/docs/api/reference#send-chat-announcement
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function sendChatAnnouncement(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);
        $this->validateRequired($body, ['message']);

        return $this->post('chat/announcements', $parameters, null, $body);
    }

    /**
     * Gets the color used for the user’s name in chat.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-user-chat-color
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getUserChatColor(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['user_id']);

        return $this->get('chat/color', $parameters);
    }

    /**
     * Updates the color used for the user’s name in chat.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-user-chat-color
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function updateUserChatColor(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['user_id', 'color']);

        return $this->put('chat/color', $parameters);
    }

    /**
     * Gets the list of users that are connected to the broadcaster’s chat session.
     *
     * NOTE: There is a delay between when users join and leave a chat and when the list is updated accordingly.
     *
     * To determine whether a user is a moderator or VIP, use the Get Moderators and Get VIPs endpoints.
     * You can check the roles of up to 100 users.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-chatters
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getChatters(array $parameters = [], ?Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->get('chat/chatters', $parameters, $paginator);
    }

    /**
     * Sends a Shoutout to the specified broadcaster.Typically, you send Shoutouts when you or one of your moderators notice another broadcaster in your chat,
     * the other broadcaster is coming up in conversation, or after they raid your broadcast.
     *
     * Twitch’s Shoutout feature is a great way for you to show support for other broadcasters and help them grow.
     * Viewers who do not follow the other broadcaster will see a pop-up Follow button in your chat that they can click to follow the other broadcaster.
     *
     * Rate Limits The broadcaster may send a Shoutout once every 2 minutes. They may send the same broadcaster a Shoutout once every 60 minutes.
     *
     * To receive notifications when a Shoutout is sent or received, subscribe to the channel.shoutout.create and channel.shoutout.receive subscription types.
     * The channel.shoutout.create event includes cooldown periods that indicate when the broadcaster may send another Shoutout without exceeding the endpoint’s rate limit.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#send-a-shoutout
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function sendShoutout(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['from_broadcaster_id', 'to_broadcaster_id', 'moderator_id']);

        return $this->post('chat/shoutouts', $parameters);
    }
}
