<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientSecretException;
use romanzipp\Twitch\Exceptions\RequestRequiresRedirectUriException;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use romanzipp\Twitch\Twitch;

class ServiceGettersTest extends TestCase
{
    public function testClientIdGetterException()
    {
        $this->expectException(RequestRequiresClientIdException::class);

        $twitch = new Twitch;
        $twitch->getClientId();
    }

    public function testClientSecretGetterException()
    {
        $this->expectException(RequestRequiresClientSecretException::class);

        $twitch = new Twitch;
        $twitch->getClientSecret();
    }

    public function testTokenGetterException()
    {
        $this->expectException(RequestRequiresAuthenticationException::class);

        $twitch = new Twitch;
        $twitch->getToken();
    }

    public function testRedirectUriGetterException()
    {
        $this->expectException(RequestRequiresRedirectUriException::class);

        $twitch = new Twitch;
        $twitch->getRedirectUri();
    }
}
