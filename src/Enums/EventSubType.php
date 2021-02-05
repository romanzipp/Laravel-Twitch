<?php

namespace romanzipp\Twitch\Enums;

class EventSubType
{
    // Triggers whenever a user has revoked authorization for your client id. Use this webhook
    // to meet government requirements for handling user data, such as GDPR, LGPD, or CCPA.
    public const USER_AUTHORIZATION_REVOKE = 'user.authorization.revoke';

    // Triggers whenever a user updates their account.
    public const USER_UPDATE = 'user.update';

    /**
     * @deprecated Please use the CHANNEL_FOLLOW constant
     */
    public const USER_FOLLOW = 'channel.follow';

    // Triggers whenever a broadcaster starts their stream.
    public const STREAM_ONLINE = 'stream.online';

    // Triggers whenever a broadcaster stops their stream.
    public const STREAM_OFFLINE = 'stream.offline';

    // Triggers whenever a user follows to a broadcaster's channel.
    public const CHANNEL_FOLLOW = 'channel.follow';

    // Triggers whenever a broadcaster updates their channel.
    public const CHANNEL_UPDATE = 'channel.update';

    // Triggers whenever a viewer subscribes to a broadcaster's channel.
    public const CHANNEL_SUBSCRIBE = 'channel.subscribe';

    // Triggers whenever a viewer unsubscribes from a broadcaster's channel.
    public const CHANNEL_UNSUBSCRIBE = 'channel.unsubscribe';

    // Triggers whenever a viewer cheers on a broadcaster's channel.
    public const CHANNEL_CHEER = 'channel.cheer';

    // Triggers whenever a broadcaster raids on a broadcaster's channel.
    public const CHANNEL_RAID = 'channel.raid';

    // Triggers whenever a broadcaster hosts on a broadcaster's channel.
    public const CHANNEL_HOST = 'channel.host';

    // Triggers whenever a viewer is banned from a broadcaster's channel.
    public const CHANNEL_BAN = 'channel.ban';

    // Triggers whenever a viewer is unbanned from a broadcaster's channel.
    public const CHANNEL_UNBAN = 'channel.unban';

    // Triggers whenever a custom channel points reward has been created for a broadcaster's channel.
    public const CHANNEL_CHANNEL_POINTS_CUSTOM_REWARDS_ADD = 'channel.channel_points_custom_reward.add';

    // Triggers whenever a custom channel points reward has been updated for a broadcaster's channel.
    public const CHANNEL_CHANNEL_POINTS_CUSTOM_REWARDS_UPDATE = 'channel.channel_points_custom_reward.update';

    // Triggers whenever a custom channel points reward has been removed from a broadcaster's channel.
    public const CHANNEL_CHANNEL_POINTS_CUSTOM_REWARDS_REMOVE = 'channel.channel_points_custom_reward.remove';

    // Triggers whenever a viewer has redeemed a custom channel points reward on a broadcaster's channel.
    public const CHANNEL_CHANNEL_POINTS_CUSTOM_REWARD_REDEMPTION_ADD = 'channel.channel_points_custom_reward_redemption.add';

    // Triggers whenever a redeemed custom channel points reward has been updated for a broadcaster's channel.
    public const CHANNEL_CHANNEL_POINTS_CUSTOM_REWARD_REDEMPTION_UPDATE = 'channel.channel_points_custom_reward_redemption.update';

    // Triggers whenever a hype train begins on a broadcaster's channel.
    public const CHANNEL_HYPE_TRAIN_BEGIN = 'channel.hype_train.begin';

    // Triggers whenever a hype train makes progress on a broadcaster's channel.
    public const CHANNEL_HYPE_TRAIN_PROGRESS = 'channel.hype_train.progress';

    // Triggers whenever a hype train ends on a broadcaster's channel.
    public const CHANNEL_HYPE_TRAIN_END = 'channel.hype_train.end';
}
