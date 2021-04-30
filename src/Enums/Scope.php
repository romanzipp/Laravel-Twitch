<?php

namespace romanzipp\Twitch\Enums;

class Scope
{
    /*
     *--------------------------------------------------------------------------
     * Helix API
     *--------------------------------------------------------------------------
     */

    // View analytics data for your extensions.
    public const ANALYTICS_READ_EXTENSIONS = 'analytics:read:extensions';

    // View analytics data for your games.
    public const ANALYTICS_READ_GAMES = 'analytics:read:games';

    // View Bits information for your channel.
    public const BITS_READ = 'bits:read';

    // Run commercials on a channel.
    public const CHANNEL_EDIT_COMMERCIAL = 'channel:edit:commercial';

    // Manage a channel’s broadcast configuration, including updating channel configuration and managing stream markers and stream tags.
    public const CHANNEL_MANAGE_BROADCAST = 'channel:manage:broadcast';

    // Manage a channel’s Extension configuration, including activating Extensions.
    public const CHANNEL_MANAGE_EXTENSIONS = 'channel:manage:extensions';

    // Manage a Poll for a specific channel.
    public const CHANNEL_MANAGE_POLLS = 'channel:manage:polls';

    // Manage a Channel Points Prediction for a specific channel.
    public const CHANNEL_MANAGE_PREDICTIONS = 'channel:manage:predictions';

    // Manage Channel Points custom rewards and their redemptions on a channel.
    public const CHANNEL_MANAGE_REDEMPTIONS = 'channel:manage:redemptions';

    // Manage a channel’s videos, including deleting videos.
    public const CHANNEL_MANAGE_VIDEOS = 'channel:manage:videos';

    // View a list of users with the editor role for a channel.
    public const CHANNEL_READ_EDITORS = 'channel:read:editors';

    // View Hype Train information for a channel.
    public const CHANNEL_READ_HYPE_TRAIN = 'channel:read:hype_train';

    // View information about all Polls or specific Polls for a channel.
    public const CHANNEL_READ_POLLS = 'channel:read:polls';

    // View information about all Channel Points Predictions or specific Channel Points Predictions for a channel.
    public const CHANNEL_READ_PREDICTIONS = 'channel:read:predictions';

    // View Channel Points custom rewards and their redemptions on a channel.
    public const CHANNEL_READ_REDEMPTIONS = 'channel:read:redemptions';

    // View an authorized user’s stream key.
    public const CHANNEL_READ_STREAM_KEY = 'channel:read:stream_key';

    // Get a list of all subscribers to your channel and check if a user is subscribed to your channel
    public const CHANNEL_READ_SUBSCRIPTIONS = 'channel:read:subscriptions';

    // Manage a clip object.
    public const CLIPS_EDIT = 'clips:edit';

    // View a channel’s moderation data including Moderators, Bans, Timeouts, and Automod settings.
    public const MODERATION_READ = 'moderation:read';

    // Manage a user object.
    public const USER_EDIT = 'user:edit';

    // Edit a user’s follows.
    public const USER_EDIT_FOLLOWS = 'user:edit:follows';

    // Manage the block list of a user.
    public const USER_MANAGE_BLOCKED_USERS = 'user:manage:blocked_users';

    // View the block list of a user.
    public const USER_READ_BLOCKED_USERS = 'user:read:blocked_users';

    // Edit your channel’s broadcast configuration, including extension configuration. (This scope implies user:read:broadcast capability.)
    public const USER_EDIT_BROADCAST = 'user:edit:broadcast';

    // View your broadcasting configuration, including extension configurations.
    public const USER_READ_BROADCAST = 'user:read:broadcast';

    // Read authorized user’s email address.
    public const USER_READ_EMAIL = 'user:read:email';

    // View if an authorized user is subscribed to specific channels.
    public const USER_READ_SUBSCRIPTIONS = 'user:read:subscriptions';

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
