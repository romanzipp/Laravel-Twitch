<?php

return [
    /*
     * The Client ID to use for requests.
     */
    'client_id'     => env('TWITCH_HELIX_KEY', ''),

    /*
     * The Client Secret to use for OAuth requests.
     */
    'client_secret' => env('TWITCH_HELIX_SECRET', ''),

    /*
     * The Redirect URI to use for generating OAuth authorization.
     */
    'redirect_url'  => env('TWITCH_HELIX_REDIRECT_URI', ''),
];
