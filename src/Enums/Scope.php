<?php

namespace romanzipp\Twitch\Enums;

class Scope
{
    // View analytics data for your extensions.
    const ANALYTICS_READ_EXTENSIONS = 'analytics:read:extensions';
    // View analytics data for your games.
    const ANALYTICS_READ_GAMES = 'analytics:read:games';
    // View Bits information for your channel.
    const BITS_READ = 'bits:read';
    // Get a list of all subscribers to your channel and check if a user is subscribed to your channel
    const CHANNEL_READ_SUBSCRIPTIONS = 'channel:read:subscriptions';
    // Manage a clip object.
    const CLIPS_EDIT = 'clips:edit';
    // Manage a user object.
    const USER_EDIT = 'user:edit';
    // Edit your channel’s broadcast configuration, including extension configuration. (This scope implies user:read:broadcast capability.)
    const USER_EDIT_BROADCAST = 'user:edit:broadcast';
    // View your broadcasting configuration, including extension configurations.
    const USER_READ_BROADCAST = 'user:read:broadcast';
    // Read authorized user’s email address.
    const USER_READ_EMAIL = 'user:read:email';

    // Chat and PubSub

    // Perform moderation actions in a channel. The user requesting the scope must be a moderator in the channel.
    const CHANNEL_MODERATE = 'channel:moderate';
    // Send live stream chat and rooms messages.
    const CHAT_EDIT = 'chat:edit';
    // View live stream chat and rooms messages.
    const CHAT_READ = 'chat:read';
    // View your whisper messages.
    const WHISPERS_READ = 'whispers:read';
    // Send whisper messages.
    const WHISPERS_EDIT = 'whispers:edit';

    // Twitch API v5

    // Read whether a user is subscribed to your channel.
    const V5_CHANNEL_CHECK_SUBSCRIPTION = 'channel_check_subscription';
    // Trigger commercials on channel.
    const V5_CHANNEL_COMMERCIAL = 'channel_commercial';
    // Write channel metadata (game, status, etc).
    const V5_CHANNEL_EDITOR = 'channel_editor';
    // Add posts and reactions to a channel feed.
    const V5_CHANNEL_FEED_EDIT = 'channel_feed_edit';
    // View a channel feed.
    const V5_CHANNEL_FEED_READ = 'channel_feed_read';
    // Read nonpublic channel information, including email address and stream key.
    const V5_CHANNEL_READ = 'channel_read';
    // Reset a channel’s stream key.
    const V5_CHANNEL_STREAM = 'channel_stream';
    // Read all subscribers to your channel.
    const V5_CHANNEL_SUBSCRIPTIONS = 'channel_subscriptions';
    // (Deprecated — cannot be requested by new clients.) Log into chat and send messages.
    const V5_CHAT_LOGIN = 'chat_login';
    // Manage a user’s collections (of videos).
    const V5_COLLECTIONS_EDIT = 'collections_edit';
    // Manage a user’s communities.
    const V5_COMMUNITIES_EDIT = 'communities_edit';
    // Manage community moderators.
    const V5_COMMUNITIES_MODERATE = 'communities_moderate';
    // Use OpenID Connect authentication.
    const V5_OPENID = 'openid';
    // Turn on/off ignoring a user. Ignoring users means you cannot see them type, receive messages from them, etc.
    const V5_USER_BLOCKS_EDIT = 'user_blocks_edit';
    // Read a user’s list of ignored users.
    const V5_USER_BLOCKS_READ = 'user_blocks_read';
    // Manage a user’s followed channels.
    const V5_USER_FOLLOWS_EDIT = 'user_follows_edit';
    // Read nonpublic user information, like email address.
    const V5_USER_READ = 'user_read';
    // Read a user’s subscriptions.
    const V5_USER_SUBSCRIPTIONS = 'user_subscriptions';
    // Turn on Viewer Heartbeat Service ability to record user data.
    const V5_VIEWING_ACTIVITY_READ = 'viewing_activity_read';
}
