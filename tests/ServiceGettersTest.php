<?php

namespace romanzipp\Twitch\Tests;

use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Tests\TestCase;
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
        $this->expectException(RequestRequiresClientIdException::class);

        $twitch = new Twitch;
        $twitch->getClientSecret();
    }

    public function testTokenGetterException()
    {
        $this->expectException(RequestRequiresAuthenticationException::class);

        $twitch = new Twitch;
        $twitch->getToken();
    }
}
