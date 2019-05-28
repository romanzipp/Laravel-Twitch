<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Enums\Scope;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Exceptions\RequestRequiresRedirectUriException;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class RedirectUriTest extends TestCase
{
    public function testValidUri()
    {
        $this->assertEquals(
            'https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=abc&scope=' . rawurlencode('bits:read') . '%3Aread&redirect_uri=' . rawurlencode('http://localhost'),
            TwitchFacade::withClientId('abc')->withRedirectUri('http://localhost')->getOAuthAuthorizeUrl('code', [Scope::BITS_READ])
        );
    }

    public function testMissingRedirectUri()
    {
        $this->expectException(RequestRequiresRedirectUriException::class);

        TwitchFacade::withClientId('abc')->getOAuthAuthorizeUrl();
    }

    public function testMissingClientId()
    {
        $this->expectException(RequestRequiresClientIdException::class);

        TwitchFacade::withRedirectUri('http://localhost')->getOAuthAuthorizeUrl();
    }
}
