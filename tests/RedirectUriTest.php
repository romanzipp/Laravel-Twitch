<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Enums\Scope;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class RedirectUriTest extends TestCase
{
    public function testValidUri()
    {
        self::assertEquals(
            'https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=abc&scope=' . rawurlencode('bits:read') . '&redirect_uri=' . rawurlencode('http://localhost'),
            TwitchFacade::withClientId('abc')->withRedirectUri('http://localhost')->getOAuthAuthorizeUrl('code', [Scope::BITS_READ])
        );
    }
}
