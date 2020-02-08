<?php

namespace romanzipp\Twitch\Tests\TestCases;

use romanzipp\Twitch\Result;
use romanzipp\Twitch\Twitch;

abstract class ApiTestCase extends TestCase
{
    protected static $rateLimitRemaining = null;

    /**
     * @var Twitch
     */
    protected $twitch;

    protected function setUp(): void
    {
        parent::setUp();

        if ( ! $this->getClientId()) {
            $this->markTestSkipped('No Client-ID given');
        }

        if (self::$rateLimitRemaining !== null && self::$rateLimitRemaining < 3) {
            $this->markTestSkipped(
                sprintf('Rate Limit exceeded (%s)', self::$rateLimitRemaining)
            );
        }

        $this->twitch = app(Twitch::class);
        $this->twitch->setClientId(
            $this->getClientId()
        );
    }

    protected function twitch(): Twitch
    {
        return $this->twitch;
    }

    protected function registerResult(Result $result): Result
    {
        $this->assertInstanceOf(Result::class, $result);

        self::$rateLimitRemaining = $result->rateLimit('remaining');

        return $result;
    }

    protected function getClientId()
    {
        return getenv('CLIENT_ID');
    }
}
