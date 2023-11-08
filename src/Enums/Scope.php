<?php

namespace romanzipp\Twitch\Enums;

class Scope
{
    /*
     *--------------------------------------------------------------------------
     * Helix API
     *--------------------------------------------------------------------------
     */

    public const ANALYTICS_READ_EXTENSIONS = 'analytics:read:extensions';
    public const ANALYTICS_READ_GAMES = 'analytics:read:games';
    public const BITS_READ = 'bits:read';
    public const CHANNEL_MANAGE_ADS = 'channel:manage:ads';
    public const CHANNEL_READ_ADS = 'channel:read:ads';
    public const CHANNEL_MANAGE_BROADCAST = 'channel:manage:broadcast';
    public const CHANNEL_READ_CHARITY = 'channel:read:charity';
    public const CHANNEL_EDIT_COMMERCIAL = 'channel:edit:commercial';
    public const CHANNEL_READ_EDITORS = 'channel:read:editors';
    public const CHANNEL_MANAGE_EXTENSIONS = 'channel:manage:extensions';
    public const CHANNEL_READ_GOALS = 'channel:read:goals';
    public const CHANNEL_READ_GUEST_STAR = 'channel:read:guest_star';
    public const CHANNEL_MANAGE_GUEST_STAR = 'channel:manage:guest_star';
    public const CHANNEL_READ_HYPE_TRAIN = 'channel:read:hype_train';
    public const CHANNEL_MANAGE_MODERATORS = 'channel:manage:moderators';
    public const CHANNEL_READ_POLLS = 'channel:read:polls';
    public const CHANNEL_MANAGE_POLLS = 'channel:manage:polls';
    public const CHANNEL_READ_PREDICTIONS = 'channel:read:predictions';
    public const CHANNEL_MANAGE_PREDICTIONS = 'channel:manage:predictions';
    public const CHANNEL_MANAGE_RAIDS = 'channel:manage:raids';
    public const CHANNEL_READ_REDEMPTIONS = 'channel:read:redemptions';
    public const CHANNEL_MANAGE_REDEMPTIONS = 'channel:manage:redemptions';
    public const CHANNEL_MANAGE_SCHEDULE = 'channel:manage:schedule';
    public const CHANNEL_READ_STREAM_KEY = 'channel:read:stream_key';
    public const CHANNEL_READ_SUBSCRIPTIONS = 'channel:read:subscriptions';
    public const CHANNEL_MANAGE_VIDEOS = 'channel:manage:videos';
    public const CHANNEL_READ_VIPS = 'channel:read:vips';
    public const CHANNEL_MANAGE_VIPS = 'channel:manage:vips';
    public const CLIPS_EDIT = 'clips:edit';
    public const MODERATION_READ = 'moderation:read';
    public const MODERATOR_MANAGE_ANNOUNCEMENTS = 'moderator:manage:announcements';
    public const MODERATOR_MANAGE_AUTOMOD = 'moderator:manage:automod';
    public const MODERATOR_READ_AUTOMOD_SETTINGS = 'moderator:read:automod_settings';
    public const MODERATOR_MANAGE_AUTOMOD_SETTINGS = 'moderator:manage:automod_settings';
    public const MODERATOR_MANAGE_BANNED_USERS = 'moderator:manage:banned_users';
    public const MODERATOR_READ_BLOCKED_TERMS = 'moderator:read:blocked_terms';
    public const MODERATOR_MANAGE_BLOCKED_TERMS = 'moderator:manage:blocked_terms';
    public const MODERATOR_MANAGE_CHAT_MESSAGES = 'moderator:manage:chat_messages';
    public const MODERATOR_READ_CHAT_SETTINGS = 'moderator:read:chat_settings';
    public const MODERATOR_MANAGE_CHAT_SETTINGS = 'moderator:manage:chat_settings';
    public const MODERATOR_READ_CHATTERS = 'moderator:read:chatters';
    public const MODERATOR_READ_FOLLOWERS = 'moderator:read:followers';
    public const MODERATOR_READ_GUEST_STAR = 'moderator:read:guest_star';
    public const MODERATOR_MANAGE_GUEST_STAR = 'moderator:manage:guest_star';
    public const MODERATOR_READ_SHIELD_MODE = 'moderator:read:shield_mode';
    public const MODERATOR_MANAGE_SHIELD_MODE = 'moderator:manage:shield_mode';
    public const MODERATOR_READ_SHOUTOUTS = 'moderator:read:shoutouts';
    public const MODERATOR_MANAGE_SHOUTOUTS = 'moderator:manage:shoutouts';
    public const USER_EDIT = 'user:edit';
    public const USER_EDIT_FOLLOWS = 'user:edit:follows';
    public const USER_READ_BLOCKED_USERS = 'user:read:blocked_users';
    public const USER_MANAGE_BLOCKED_USERS = 'user:manage:blocked_users';
    public const USER_READ_BROADCAST = 'user:read:broadcast';
    public const USER_EDIT_BROADCAST = 'user:edit:broadcast';
    public const USER_MANAGE_CHAT_COLOR = 'user:manage:chat_color';
    public const USER_READ_EMAIL = 'user:read:email';
    public const USER_READ_FOLLOWS = 'user:read:follows';
    public const USER_READ_SUBSCRIPTIONS = 'user:read:subscriptions';
    public const USER_MANAGE_WHISPERS = 'user:manage:whispers';

    /*
     *--------------------------------------------------------------------------
     * Chat & PubSub
     *--------------------------------------------------------------------------
     */

    // Perform moderation actions in a channel. The user requesting the scope must be a moderator in the channel.
    public const CHANNEL_MODERATE = 'channel:moderate';

    // Send live stream chat and rooms messages.
    public const CHAT_EDIT = 'chat:edit';

    // View live stream chat and rooms messages.
    public const CHAT_READ = 'chat:read';

    // View your whisper messages.
    public const WHISPERS_READ = 'whispers:read';

    // Send whisper messages.
    public const WHISPERS_EDIT = 'whispers:edit';

    /*
     *--------------------------------------------------------------------------
     * v5 API
     *--------------------------------------------------------------------------
     */

    // Read whether a user is subscribed to your channel.
    public const V5_CHANNEL_CHECK_SUBSCRIPTION = 'channel_check_subscription';

    // Trigger commercials on channel.
    public const V5_CHANNEL_COMMERCIAL = 'channel_commercial';

    // Write channel metadata (game, status, etc).
    public const V5_CHANNEL_EDITOR = 'channel_editor';

    // Add posts and reactions to a channel feed.
    public const V5_CHANNEL_FEED_EDIT = 'channel_feed_edit';

    // View a channel feed.
    public const V5_CHANNEL_FEED_READ = 'channel_feed_read';

    // Read nonpublic channel information, including email address and stream key.
    public const V5_CHANNEL_READ = 'channel_read';

    // Reset a channel’s stream key.
    public const V5_CHANNEL_STREAM = 'channel_stream';

    // Read all subscribers to your channel.
    public const V5_CHANNEL_SUBSCRIPTIONS = 'channel_subscriptions';

    // (Deprecated — cannot be requested by new clients.) Log into chat and send messages.
    public const V5_CHAT_LOGIN = 'chat_login';

    // Manage a user’s collections (of videos).
    public const V5_COLLECTIONS_EDIT = 'collections_edit';

    // Manage a user’s communities.
    public const V5_COMMUNITIES_EDIT = 'communities_edit';

    // Manage community moderators.
    public const V5_COMMUNITIES_MODERATE = 'communities_moderate';

    // Use OpenID Connect authentication.
    public const V5_OPENID = 'openid';

    // Turn on/off ignoring a user. Ignoring users means you cannot see them type, receive messages from them, etc.
    public const V5_USER_BLOCKS_EDIT = 'user_blocks_edit';

    // Read a user’s list of ignored users.
    public const V5_USER_BLOCKS_READ = 'user_blocks_read';

    // Manage a user’s followed channels.
    public const V5_USER_FOLLOWS_EDIT = 'user_follows_edit';

    // Read nonpublic user information, like email address.
    public const V5_USER_READ = 'user_read';

    // Read a user’s subscriptions.
    public const V5_USER_SUBSCRIPTIONS = 'user_subscriptions';

    // Turn on Viewer Heartbeat Service ability to record user data.
    public const V5_VIEWING_ACTIVITY_READ = 'viewing_activity_read';
}
