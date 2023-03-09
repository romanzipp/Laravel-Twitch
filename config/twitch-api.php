<?php

use romanzipp\Twitch\Repositories\TwitchClientCredentialsRepository;

return [
    /*
     * The Client ID to use for requests.
     */
    'client_id' => env('TWITCH_HELIX_KEY'),

    /*
     * The Client Secret to use for OAuth requests.
     */
    'client_secret' => env('TWITCH_HELIX_SECRET'),

    /*
     * The Redirect URI to use for generating OAuth authorization.
     */
    'redirect_url' => env('TWITCH_HELIX_REDIRECT_URI'),

    'oauth_client_credentials' => [

        /*
         * You can choose between the following oauth client credentials cache stores:
         *  - TwitchClientCredentialsRepository
         *  - GoogleClientCredentialsRepository
         *  - AwsClientCredentialsRepository
         *
         * We recommend using RedisClientCredentialsRepository for most use cases.
         */
        'provider' => TwitchClientCredentialsRepository::class,

        /*
         * Since May 01, 2020, Twitch requires all API requests to contain a valid Access Token.
         * This can be achieved with the Client Credentials flow.
         *
         * The package will attempt to generate a Access Token for unauthenticated requests.
         * NOTICE: This will only be enabled if a Client ID and Client Secret have been specified.
         */
        'auto_generate' => true,

        /*
         * Enable caching the Access Token to minimize workload.
         */
        'cache' => true,

        /*
        * The cache store to use for storing Client Credentials.
        */
        'cache_store' => null,

        /*
         * The cache key to use for storing information.
         */
        'cache_key' => 'twitch-api-client-credentials',

        /*
         * Configurations for the different credential providers.
         */
        'secret_manager' => [

            /*
             * Google secret manager configuration.
             */
            'google' => [
                'project_id' => env('TWITCH_HELIX_SECRET_MANAGER_PROJECT_ID'),
                'secret_id' => env('TWITCH_HELIX_SECRET_MANAGER_SECRET_ID', 'twitch_access_token'),
                'secret_version' => env('TWITCH_HELIX_SECRET_MANAGER_SECRET_VERSION', 'latest'),
            ],

            /*
             * Amazon secret manager configuration.
             */
            'aws' => [
                'secret_id' => env('TWITCH_HELIX_SECRET_MANAGER_SECRET_ID', 'twitch_access_token'),
            ],
        ],
    ],

    'eventsub' => [
        /*
         * Secret used to generate the signature.
         */
        'secret' => env('TWITCH_HELIX_EVENTSUB_SECRET'),

        /*
         * Maximum difference (in seconds) allowed between the header's timestamp and the current time.
         */
        'tolerance' => 600,
    ],
];
