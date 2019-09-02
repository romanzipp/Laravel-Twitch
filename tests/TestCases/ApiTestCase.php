<?php

namespace romanzipp\Twitch\Tests\TestCases;

use romanzipp\Twitch\Facades\Twitch;
use romanzipp\Twitch\Result;

abstract class ApiTestCase extends TestCase
{
    protected static $rateLimitRemaining = null;

    protected function setUp(): void
    {
        parent::setUp();

        if ( ! $this->getClientId()) {
            $this->markTestSkipped('No Client-ID given');
        }

        if (self::$rateLimitRemaining !== null && self::$rateLimitRemaining < 3) {
            $this->markTestSkipped('Rate Limit exceeded (' . self::$rateLimitRemaining . ')');
        }

        Twitch::setClientId($this->getClientId());
    }

    protected function registerResult(Result $result): Result
    {
        self::$rateLimitRemaining = $result->rateLimit('remaining');

        return $result;
    }

    protected function getClientId()
    {
        return getenv('CLIENT_ID');
    }
}
