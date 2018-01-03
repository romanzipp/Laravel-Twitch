<?php

return [
    'client_id' => env('TWITCH_NEW_KEY', ''),
    'client_secret' => env('TWITCH_NEW_SECRET', ''),
    'redirect_url' => env('TWITCH_NEW_REDIRECT_URI', ''),
    'scopes' => [
        'channel_check_subscription',
        'channel_commercial',
        'channel_editor',
        'channel_feed_edit',
        'channel_feed_read',
        'channel_read',
        'channel_stream',
        'channel_subscriptions',
        'chat_login',
        'collections_edit',
        'communities_edit',
        'communities_moderate',
        'openid',
        'user_blocks_edit',
        'user_blocks_read',
        'user_follows_edit',
        'user_read',
        'user_subscriptions',
        'viewing_activity_read'
    ],
];
